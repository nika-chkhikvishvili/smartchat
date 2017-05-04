/**
 * Created by jedi on 2/23/16.
 */

'use strict';

var Message   = require('./models/Message');
var GuestUser = require('./models/GuestUser');
var Chat      = require('./models/Chat');
var ChatRoom  = require('./models/ChatRoom');
var log;
// var app;


function ChatClient(data) {
    if (!(this instanceof ChatClient)) return new ChatClient(data);
    this.app = data;
    log = data.log;
}

ChatClient.prototype.clientGetServices = function (socket) {
    app.connection.query('SELECT `cs`.`category_service_id`, `rc`.`repository_id`, `rc`.`category_name`, `cs`.`service_name_geo`, `cs`.`start_time`, `cs`.`end_time` ' +
        ' FROM `category_services` cs, `repo_categories` rc ' +
        ' WHERE cs.`repo_category_id` = rc.`repo_category_id`',
        function (err, rows, fields) {
            if (err) return app.databaseError(socket, err);
            socket.emit('clientGetServicesResponse', rows);
        });
};

ChatClient.prototype.clientInitParams = function (socket, data) {
    //შეამოწმებს სერვისის სამუშაო პერიოდს თუ არ გასცდა
    app.connection.query('SELECT start_time, end_time  FROM category_services WHERE category_service_id = ? ', [data.service_id], function (err, res) {
        if (err)  return app.databaseError(socket, err);

        if  (!res || !Array.isArray(res) || res.length !== 1) {
            socket.emit("clientInitParamsResponse", {isValid: false});
            return ;
        }

        var startTime = Date.parse('01/01/2000 '+ res[0].start_time);
        var endTime   = Date.parse('01/01/2000 '+ res[0].end_time);
        var nowTime = new Date();
        nowTime.setFullYear(2000,0,1);

        if (startTime !== endTime) {
            if (startTime > nowTime.getTime() || endTime < nowTime.getTime()) {
                socket.emit("clientInitParamsResponse", {serviceIsOffline: true});
                return ;
            }
        }

        var guestUser = new GuestUser({firstName: data.first_name, lastName: data.last_name, ip: socket.conn.remoteAddress});

        app.connection.query('INSERT INTO `online_users` SET ? ', guestUser.getInsertObject(), function (err, res) {
            if (err)  return app.databaseError(socket, err);

            guestUser.guestUserId = res.insertId;
            socket.guestUserId =  guestUser.guestUserId;


            if (!app.waitingClients[data.service_id] || app.waitingClients[data.service_id] === null) {
                app.waitingClients[data.service_id] = fifo();
            }

            var chat = new Chat({serviceId : data.service_id, guestUserId : guestUser.guestUserId, guestUser : guestUser});

            app.connection.query('INSERT INTO `chats` SET ? ', chat.getInsertObject(), function (err, res) {
                if (err) return me.databaseError(socket, err);
                chat.chatId = res.insertId;

                var chatRoom = new ChatRoom({chat: chat});
                chatRoom.guests = [socket.id];

                app.connection.query('INSERT INTO `chat_rooms` SET ? ', chatRoom.getInsertGuestObject(), function (err, res1) {
                    if (err) return me.databaseError(socket, err);
                    chatRoom.chatRoomId = res1.insertId;

                    app.chatRooms[chat.chatUniqId] = chatRoom;
                    app.waitingClients[data.service_id].push(chatRoom);
                    //შეამოწმებს ვის შეუძლია უპასუხოს ამ კლიენტს და ავტომატურად დაამატებს ჩატში
                    app.checkAvailableOperatorForService(socket, data.service_id);
                    //უგზავნის სუყველას შეტყობინებას რომ ახალი მომხმარებელი შემოვიდა
                    app.io.emit('checkClientCount');
                    socket.emit("clientInitParamsResponse", {chatUniqId: chat.chatUniqId});
                });
            });
        });
    });
};

ChatClient.prototype.clientCheckChatIfAvailable = function (socket, data) {
    if (!data || !data.hasOwnProperty('chatUniqId') || !data.chatUniqId || data.chatUniqId.length < 10) {
        socket.emit("clientCheckChatIfAvailableResponse", {isValid: false});
        return;
    }

    app.connection.query('SELECT * FROM  `chats` WHERE  chat_uniq_id = ?', [data.chatUniqId], function (err, res) {
        if (err) return app.databaseError(socket, err);

        if  (!(res && Array.isArray(res) && res.length === 1)) {
            socket.emit("clientCheckChatIfAvailableResponse", {isValid: false});
            return ;
        }

        var ans = res[0];
        socket.guestUserId = ans.online_user_id;

        //ამოწმებს არის თუ არა ეს მომხმარებელი დამატებული ჩატის ოთახში
        var isAdded = false;
        var chatRoom = app.chatRooms[data.chatUniqId];

        chatRoom.guests.forEach(function (socketId) {
            isAdded = isAdded || ( socketId === socket.id);
        });

        if (!isAdded) {
            //თუ არ არის დაამატებს
            chatRoom.guests.push(socket.id);
        }


        app.connection.query('SELECT * FROM  `online_users` WHERE online_user_id = ?', [ans.online_user_id], function (err, res) {
            if (err) return app.databaseError(socket, err);

            if (!(res && Array.isArray(res) && res.length === 1)) {
                socket.emit("clientCheckChatIfAvailableResponse", {isValid: false});
                return ;
            }

            var user = res[0];
            app.connection.query('SELECT m.`chat_message_id`, m.`chat_id`, m.`person_id`, m.`online_user_id`, m.`chat_message`, m.`message_date`' +
                'FROM `smartchat`.`chat_messages` m where m.`chat_id` = ? order by   m.`message_date` asc', [ans.chat_id], function (err, res) {
                if (err) return me.databaseError(socket, err);

                socket.emit("clientCheckChatIfAvailableResponse", {
                    isValid: true, first_name: user.online_users_name,
                    last_name: user.online_users_lastname, messages: res
                });
            });
        });
    });
};

ChatClient.prototype.clientMessage = function (socket, data) {
    if (!data || !data.hasOwnProperty('chatUniqId') || !data.chatUniqId || data.chatUniqId.length < 10) {
        socket.emit("clientMessageResponse", {isValid: false, error: 'chatUniqId', data: data});
        return;
    }

    if (!app.chatRooms || !app.chatRooms.hasOwnProperty(data.chatUniqId)) {
        socket.emit("clientMessageResponse", {isValid: false, error: 'chatRooms'});
        return;
    }

    var chat = app.chatRooms[data.chatUniqId];

    var message = new Message( {chatId: chat.chatId, guestUserId: socket.guestUserId, message: data.message});

    app.connection.query('INSERT INTO `chat_messages` SET ? ', message.getInsertObject(), function (err, res) {
        if (err) return me.databaseError(socket, err);

        message.messageId     = res.insertId;
        message.chatUniqId    = data.chatUniqId;
        message.messageUniqId = data.id;

        app.sendMessageToRoom(socket, message);
        socket.emit("clientMessageResponse", res);

    });
};

ChatClient.prototype.clientMessageReceived = function (socket, data) {
    if (!data || !data.hasOwnProperty('msgId') || !data.msgId) {
        return;
    }

    if (!app.chatRooms || !app.chatRooms.hasOwnProperty(data.chatUniqId)) {
        return;
    }

    app.sendMessageReceivedToRoom(socket, data.chatUniqId, data.msgId);
};

ChatClient.prototype.clientCloseChat = function (socket, data) {
    if (!data || !data.hasOwnProperty('chatUniqId') || !data.chatUniqId) {
        return;
    }

    if (!app.chatRooms || !app.chatRooms.hasOwnProperty(data.chatUniqId)) {
        return;
    }

    app.connection.query('UPDATE  chats SET chat_status_id = 3 WHERE chat_uniq_id = ?', [data.chatUniqId], function (err, res) {
        if (err) return me.databaseError(socket, err);
        var message = new Message();
        message.chatUniqId = data.chatUniqId;
        message.messageType = 'close';

        app.sendMessageToRoom(socket, message, true);
    });
};

ChatClient.prototype.userIsWriting = function (socket, data) {
    if (!data || !data.hasOwnProperty('chatUniqId') || !data.chatUniqId || data.chatUniqId.length < 10) {
        return;
    }

    if (!app.chatRooms || !app.chatRooms.hasOwnProperty(data.chatUniqId)) {
        return;
    }

    var message = new Message();
    message.chatUniqId = data.chatUniqId;
    message.messageType = 'writing';

    app.sendMessageToRoom(socket, message);
};

module.exports = ChatClient;

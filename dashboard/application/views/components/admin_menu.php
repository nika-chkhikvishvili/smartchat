<?php
$session_data = $this->session->userdata('user');
$this->db->select('*');
$this->db->from('person_roles');
$this->db->where('person_id',$session_data->person_id);
$query = $this -> db ->get();
$res = $query->result_array();

?>
<ul>
    <li>
        <a href="<?= base_url(); ?>dashboard" class="waves-effect active"><i class="md md-home"></i><span> მთავარი გვერდი </span></a>
    </li>

	<?php
    foreach ($res as $get):
    if(@$get['role_id'] ==1){        
    
    ?>
    <li>
    <a href="<?= base_url(); ?>history" class="waves-effect"><i class="md md-event"></i><span> ისტორია </span></a>
    </li>
	<?php
    };
	endforeach;
	?>
	<?php
   
    if($session_data->is_admin ==1){        
    
    ?>
    <li>
    <a href="<?= base_url(); ?>history" class="waves-effect"><i class="md md-event"></i><span> ისტორია </span></a>
    </li>
	<?php
    };
	
	?>
	<?php
    
    if($session_data->is_admin ==1){        
    
    ?>
     <li>
    <a href="<?= base_url(); ?>stattistics" class="waves-effect"><i class="fa fa-database"></i><span> სტატისტიკა </span></a>
    </li>
	<?php
    };
	?>
    <?php
    
    if($session_data->is_admin ==1){        
    
    ?>
    <li class="has_sub">
        <a href="#" class="waves-effect"><i class="md md-palette"></i> <span> ადმინისტრირება </span> <span
        class="pull-right"><i class="md md-add"></i></span></a>
        <ul class="list-unstyled">
            <li><a href="<?= base_url(); ?>institution">უწყებების მართვა</a></li>
            <li><a href="<?= base_url(); ?>services">სერვისების მართვა</a></li>
            <li><a href="<?= base_url(); ?>persons">მომხმარებლის მართვა</a></li>
            <li><a href="<?= base_url(); ?>dashboard/system">სისტემის მართვა</a></li>
            <li><a href="<?=base_url();?>templates">შაბლონების მართვა</a></li>
            
            
        </ul>
    </li>
   
    <li>
<a href="<?= base_url(); ?>dashboard/answering" class="waves-effect"><i class="md md-cast-connected"></i><span> ავტომოპასუხე </span></a>
</li>
    <li>
<a href="<?= base_url(); ?>blacklist" class="waves-effect"><i class="md md-block"></i><span> შავი სია </span></a>
</li>
<li>
<a href="<?= base_url(); ?>notready" class="waves-effect"><i class="md md-event-busy"></i><span> ხელმისაწვდომობა </span></a>
</li>	
 <li>
<a href="<?=base_url();?>files" class="waves-effect"><i class="md  md-attach-file"></i><span> ფაილების მენეჯმენტი </span></a>
</li>
 <?php
    };
?>

<?php
foreach ($res as $get):    
if(@$get['role_id'] ==7){        

?>
 <li>
<a href="<?=base_url();?>files" class="waves-effect"><i class="md  md-attach-file"></i><span> ფაილების მენეჯმენტი </span></a>
</li>
 <?php
    };
	endforeach;
?>

<?php
foreach ($res as $get):    
if(@$get['role_id'] ==8){        

?>
<li><a href="<?=base_url();?>profile"><i class="md ion-android-contact"></i><span>პირადი პარამეტრები</span></a></li>
 <?php
    };
	endforeach;
?>
<li><a href="<?=base_url();?>logout"><i class="glyphicon glyphicon-log-out"></i><span>გასვლა</span></a></li>  
</ul>
<div class="clearfix"></div>

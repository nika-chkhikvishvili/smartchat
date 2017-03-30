<!DOCTYPE html>
<html>
<head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <meta name="description" content="Smart Logic  Open Source Chat System">
        <meta name="author" content="Coderthemes">
        <link rel="shortcut icon" href="<?=base_url();?>assets/images/favicon_1.ico">
        <title>ჩეთის ადმინისტრატორი</title>
        <link href="<?=base_url();?>assets/plugins/nestable/jquery.nestable.css" rel="stylesheet">
        <link href="<?=base_url();?>assets/css/bootstrap.min.css" rel="stylesheet" type="text/css">
        <link href="<?=base_url();?>assets/css/core.css" rel="stylesheet" type="text/css">
        <link href="<?=base_url();?>assets/css/icons.css" rel="stylesheet" type="text/css">
        <link href="<?=base_url();?>assets/css/components.css" rel="stylesheet" type="text/css">
        <link href="<?=base_url();?>assets/css/pages.css" rel="stylesheet" type="text/css">
        <link href="<?=base_url();?>assets/css/menu.css" rel="stylesheet" type="text/css">
        <link href="<?=base_url();?>assets/css/responsive.css" rel="stylesheet" type="text/css">
        <script src="<?=base_url();?>assets/js/modernizr.min.js"></script>
        <link href="<?=base_url();?>assets/plugins/notifications/notification.css" rel="stylesheet">
	<link href="<?=base_url();?>assets/plugins/modal-effect/css/component.css" rel="stylesheet">
        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
        <![endif]-->

        <script src="<?=base_url();?>assets/js/jquery.min.js"></script>
        <script src="<?=base_url();?>assets/js/bootstrap.min.js"></script>
		<script type="text/javascript">
		$(document).ready(function(){
		  $('td.editable-col').on('focusout', function() {
			data = {};
			data['val'] = $(this).text();
			data['id'] = $(this).parent('tr').attr('data-row-id');
			data['index'] = $(this).attr('col-index');
			if($(this).attr('oldVal') === data['val'])
			return false;
			
			if(confirm('განვაახლოთ მონაცემები ?'))
				 {
				  $.ajax({
				  type: "POST",  
				  url: "http://localhost/chat/dashboard/update_institution",  
				  cache:false,  
				  data: data,
				  dataType: "json",       
				  success: function(response)  
				  {   
					//$("#loading").hide();
					if(response.status) {
					  $.Notification.notify('success','top center', 'ყურადღება', response.msg);
					  setTimeout(function(){window.location.reload(1); }, 3000);		
					} else {
					  $.Notification.notify('success','top center', 'ყურადღება', response.msg);
					  setTimeout(function(){window.location.reload(1); }, 3000);		
					}
				  }   
				});
				}
			});
		     // delete the entry once we have confirmed that it should be deleted
			$('.delete').click(function() {
			data = {};			
			data['id'] = $(this).parent('tr').attr('data-row-id');
				var parent = $(this).closest('tr');
				 if(confirm('დარწმუნებული ხართ რომ გინდათ უწყების წაშლა?'))
				 {
				 $.ajax({
					type: "POST",  
					  url: "http://localhost/chat/dashboard/delete_institution",  
					  cache:false,  
					  data: data,
					  dataType: "json",   
					beforeSend: function() {
						parent.animate({'backgroundColor':'#fb6c6c'},300);
					},
					success: function(response) {
						
					//$("#loading").hide();
						if(response.status) {
						  $.Notification.notify('success','top center', 'ყურადღება', response.msg);
						  setTimeout(function(){window.location.reload(1); }, 3000);		
						} else {
						   $.Notification.notify('success','top center', 'ყურადღება', response.msg);
						   setTimeout(function(){window.location.reload(1); }, 3000);		
						}
					}
				});	 
				 }
				       
			});
			
		});

		</script>
    </head>


    <body class="fixed-left">
        
        <!-- Begin page -->
        <div id="wrapper">
        
            <!-- Top Bar Start -->
			<?php require_once('components/admin_topbar.php');?>
            <!-- Top Bar End -->


            <!-- ========== Left Sidebar Start ========== -->

            <div class="left side-menu">
                <div class="sidebar-inner slimscrollleft">
                    <div class="user-details">
                        <div class="pull-left">
                            <img src="<?=base_url();?>assets/images/users/girl.png" alt="" class="thumb-md img-circle">
                        </div>
                        <div class="user-info">
                           <div class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">ნატალია<span class="caret"></span></a>
                                <ul class="dropdown-menu">
                                    <li><a href="javascript:void(0)"><i class="md md-face-unlock"></i> Profile<div class="ripple-wrapper"></div></a></li>
                                    <li><a href="javascript:void(0)"><i class="md md-settings"></i> Settings</a></li>
                                    <li><a href="javascript:void(0)"><i class="md md-lock"></i> Lock screen</a></li>
                                    <li><a href="javascript:void(0)"><i class="md md-settings-power"></i> Logout</a></li>
                                </ul>
                            </div>
                            
                            <p class="text-muted m-0">ადმინისტრატორი</p>
                        </div>
                    </div>
                    <!--- Divider -->
                    <div id="sidebar-menu">
                     <?php require_once('components/admin_menu.php'); ?>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
            <!-- Left Sidebar End --> 
            <!-- ============================================================== -->
            <!-- Start right Content here -->
            <!-- ============================================================== -->                      
            <div class="content-page">
                <!-- Start content -->
                <div class="content">
                    <div class="container">
                    <div class="row">
                    <div class="col-md-12">
                    <div class="panel panel-default">
		<div class="panel-heading"><h3 class="panel-title">ახალი შაბლონის დამატება</h3></div>
		<div class="panel-body">
<?php echo validation_errors('<div class="col-lg-offset-2 col-lg-9"><div class="alert alert-warning">', '</div></div>'); ?>
<?php if(!empty($error_message)) {  echo '<div class="col-lg-offset-2 col-lg-9"><div class="alert alert-warning">'.$error_message.'</div></div>'; }?> 


                    
                    <div class="form"> 
  
 <form class="cmxform form-horizontal tasi-form" id="commentForm" method="POST" action="">
  <div class="form-group">
    <label for="cname" class="control-label col-lg-2">სერვისის დასახელება</label>
    <div class="col-lg-9">
       <select class="select2 form-control" data-placeholder="Choose a Country..." name="service_id">
        <option value="0">ყველა სერვისისთვის</option>
          <?php 
                foreach ($get_sql_services as $institutions):														  
          ?>
          <option value="<?php echo $institutions['repo_category_id'];?>"><?php echo $institutions['service_name_geo'];?></option>
          <?php endforeach; ?>
   </select>
    </div>
  </div>
<div class="form-group">
<label for="cname" class="control-label col-lg-2">შაბლონის ტექსტი <img src="<?=base_url();?>assets/flags/geo.png" data-toggle="tooltip"  data-placement="bottom" title="ქართული"></label>
<div class="col-lg-9">
<input class="form-control" id="cname" name="template_text_ge" type="text">
</div>
</div>
<div class="form-group">
<label for="cname" class="control-label col-lg-2"> <img src="<?=base_url();?>assets/flags/rus.png" data-toggle="tooltip" data-placement="bottom" title="რუსული"></label>
<div class="col-lg-9">
<input class="form-control" id="cname" name="template_text_ru" type="text" >
</div>
</div>
<div class="form-group">
<label for="cname" class="control-label col-lg-2"> <img src="<?=base_url();?>assets/flags/usa.png" data-toggle="tooltip" data-placement="bottom" title="ინგლისური"></label>
<div class="col-lg-9">
<input class="form-control" id="cname" name="template_text_en" type="text" >
</div>
</div>  

		
<div class="form-group">
<div class="col-lg-offset-2 col-lg-9">
   <input type="submit" class="btn btn-primary" type="submit" name="add_tem" value="შენახვა" />
   <input type="reset" class="btn btn-danger" type="submit"   value="გასუფთავება" />
</div>
</div>
</form>
</div> <!-- .form -->
<div class="col-lg-offset-2 col-lg-9"><div id="msg" class="alert">                                         

</div></div>
</div> <!-- panel-body -->
</div> <!-- panel -->
					</div>
					
               <div class="md-modal md-effect-6" id="modal-7">
               <div class="md-content">
                       <h3>ინფორმაცია</h3>
                       <div>
                                <div class="tab-pane" id="profile-2">
                                 <!-- Personal-Information -->
                                 <div class="panel panel-default panel-fill">

                                       <div class="panel-body"> 
                                               <div class="timeline-2">
                                               <div class="time-item">
                                                       <div class="item-info">
                                                               <div class="text-muted">09:00:53</div>
                                                               <p><strong>სისტემაში ავტორიზაცია </strong></p>
                                                       </div>
                                               </div>

                                               <div class="time-item">
                                                       <div class="item-info">
                                                               <div class="text-muted">30 minutes ago</div>
                                                               <p><a href="#" class="text-info">Lorem</a> commented your post.</p>

                                                       </div>
                                               </div>

                                               <div class="time-item">
                                                       <div class="item-info">
                                                               <div class="text-muted">59 minutes ago</div>
                                                               <p><a href="#" class="text-info">Jessi</a> attended a meeting with<a href="#" class="text-success">John Doe</a>.</p>

                                                       </div>
                                               </div>
                                       </div>

                                       </div> 
                               </div>
                               <!-- Personal-Information -->
                               </div> 

				<button class="md-close btn btn-primary waves-effect waves-light">დახურვა</button>
				</div>
				</div>
                     </div>
					  <!-- end of modal info -->
                        </div>
                        <!-- Start Widget -->
                      
                        <div class="row">
                            <div class="col-md-12">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h3 class="panel-title">შაბლონები</h3>
                                    </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>                                                               
                                        <th>ქართული</th>
                                        <th>რუსული</th>
                                        <th>ინგლისური</th>
                                        <th>კატეგორია</th>
                                        <th>ქმედება</th>                                                                
                                    </tr>
                                </thead>
                                <tbody>
                                      <?php
                                        if(!empty($get_sql_templates))
                                        {                                           
                                            foreach ($get_sql_templates as $templates):
                                        
                                    ?>
                                    <tr>                                                                
                                        <td><?php echo $templates['template_text_ge']; ?></td>
                                        <td><?php echo $templates['template_text_ru']; ?></td>
                                        <td><?php echo $templates['template_text_en']; ?></td>                                        
                                        <td><?php echo $templates['service_name_geo']; ?></td>
                                        <td>
                                            <a href="<?php echo $templates['message_templates_id']; ?>">რედაქტირება</a>
                                            <a href="<?php echo $templates['message_templates_id']; ?>">წაშლა</a>
                                        </td>
                                         
                                    </tr>
                                    <?php
                                    endforeach; }
                                    ?>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
                                </div>
                            </div>
                        </div> <!-- End row -->
						<!-- end row -->

                    </div> <!-- container -->
                               
                </div> <!-- content -->

                <footer class="footer text-right">
                    2016 © Smart Logic.
                </footer>

            </div>
            <!-- ============================================================== -->
            <!-- End Right content here -->
            <!-- ============================================================== -->


            <!-- Right Sidebar -->
            <?php require_once('components/admin_online_chatlist.php');?>
            <!-- /Right-bar -->

        </div>
        <!-- END wrapper -->


    
  
         <script>
            var resizefunc = [];
        </script>

        <!-- jQuery  -->
        
      
        <script src="<?=base_url();?>assets/js/detect.js"></script>
        <script src="<?=base_url();?>assets/js/fastclick.js"></script>
        <script src="<?=base_url();?>assets/js/jquery.slimscroll.js"></script>
        <script src="<?=base_url();?>assets/js/jquery.blockUI.js"></script>
        <script src="<?=base_url();?>assets/js/waves.js"></script>
        <script src="<?=base_url();?>assets/js/wow.min.js"></script>
        <script src="<?=base_url();?>assets/js/jquery.nicescroll.js"></script>
        <script src="<?=base_url();?>assets/js/jquery.scrollTo.min.js"></script>

        <script src="<?=base_url();?>assets/js/jquery.app.js"></script>
     
        <script src="<?=base_url();?>assets/plugins/notifications/notify-metro.js"></script>
        <script src="<?=base_url();?>assets/plugins/notifications/notifications.js"></script>
      
        <!--script for this page only-->
        <script src="<?=base_url();?>assets/plugins/nestable/jquery.nestable.js"></script>
        
        <!-- Modal-Effect -->
        <script src="<?=base_url();?>assets/plugins/modal-effect/js/classie.js"></script>
        <script src="<?=base_url();?>assets/plugins/modal-effect/js/modalEffects.js"></script>
        
        <script src="<?=base_url();?>assets/plugins/notifyjs/dist/notify.min.js"></script>
        <script src="<?=base_url();?>assets/plugins/notifications/notify-metro.js"></script>
        <script src="<?=base_url();?>assets/plugins/notifications/notifications.js"></script>
    
    </body>

</html>
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class stattistics extends CI_Controller{

    function __construct(){
        parent::__construct();
        $session_data = $this->session->userdata('user');
        $this->load->library('vsession');
        $this->vsession->check_person_sessions($session_data);
        $this->lang->load('ge');
        
        
    }


    public function index(){
       $data['notify'] = ""; 
       $this->load->model('dashboard_model'); 
       $session_data = $this->session->userdata('user');
       $data['get_services'] = $this->dashboard_model->get_services($session_data->repo_id);
       $data['get_persons'] = $this->dashboard_model->get_persons();
      
       $this->load->view('stattistics', $data);
    }
    
    
    function get_all_data()
    {
        $session_data = $this->session->userdata('user');
        $this->load->model('dashboard_model');
        
        if(@$_POST['service_id'] || @$_POST['user_id'] || @$_POST['date']){
        $service_id = "";
        $user_id = "";
        $by_date = "";
        if(@$_POST['service_id']>=1 || @$_POST['user_id'] || $_POST['date']!="")
        {
          $service_id = @$_POST['service_id'];
          $user_id = @$_POST['user_id'];
          $by_date = @$_POST['date'];
        }
        $waiting = $this->dashboard_model->get_statistic_byarg($service_id,$user_id,$by_date,0);
        $active = $this->dashboard_model->get_statistic_waiting($service_id,$user_id,$by_date,1);
        $redirecting = $this->dashboard_model->get_statistic_waiting($service_id,$user_id,$by_date,2);
        $closed = $this->dashboard_model->get_statistic_waiting($service_id,$user_id,$by_date,3); 
       
        echo '
	<ul class="list-group">
        <li class="list-group-item">
            <span class="badge badge-primary">'.$waiting.'</span>
           საუბრის დაწყების მოლოდინში
        </li>
        <li class="list-group-item">
            <span class="badge badge-purple">'.$active.'</span>
            საუბარში ჩართული მომხამრებლები
        </li>
        <li class="list-group-item">
            <span class="badge badge-inverse">'.$redirecting.'</span>
            სულ გადამისამართებული
        </li>
        <li class="list-group-item">
            <span class="badge badge-pink">'.$closed.'</span>
           სულ საუბრები
        </li>
        </ul>';
        
        
        }
        else 
        {
        $waiting = $this->dashboard_model->get_statistic_waiting(0);
        
        $active = $this->dashboard_model->get_statistic_waiting(1);
        $redirecting = $this->dashboard_model->get_statistic_waiting(2);
        $closed = $this->dashboard_model->get_statistic_waiting(3); 
        
        // საერთო ვიზიტორების რაოდენობა
        $all_chats = $this->dashboard_model->get_statistic_allchats(); 
        echo '  <table class="table table-condensed">
                <thead>
                <tr>
                </tr>
                </thead>
                <tbody>
                
                <tr class="success">
                <td>საერთო ვიზიტორების რაოდენობა</td>
                <td class="text-center"></td>
                <td class="text-center"></td>
                <td class="text-right"><span class="badge"> 
                                   '.$all_chats.'</span></td>
                </tr>
                ';
                
                //საერთო ვიზიტორების რაოდენობა კატეგორიის მიხედვით :
                $sql_get_services = $this->dashboard_model->get_all_services(); 
                foreach($sql_get_services as $keys => $values){                 
		  $sql_get_services[$keys]['all_chats'] = $this->dashboard_model->get_by_service_id($values['category_service_id']); 
                }
               
                echo '<tr class="warning"><td>საერთო ვიზიტორების რაოდენობა კატეგორიის მიხედვით :</td>
                <td class="text-center"></td>
                <td class="text-center"></td>
                <td class="text-right"></td>
                </tr>';
                foreach($sql_get_services as $services){

                  echo "<tr>
                        <td class='thick-line'></td>
                        <td class='thick-line'></td>
                        <td class='thick-line text-center'>".$services['service_name_geo']."</td>
                        <td class='thick-line text-right'>".$services['all_chats']."</td>
                        </tr>";          
                }
                // საერთო ვიზიტორების რაოდენობა ოპერატორების მიხედვით 
                echo '<tr class="warning"> <td>საერთო ვიზიტორების რაოდენობა ოპერატორების მიხედვით :</td>
                <td class="text-center"></td>
                <td class="text-center"></td>
                <td class="text-right"></td>
                </tr>';
                
                 $sql_get_operators = $this->dashboard_model->get_all_persons();
                 foreach($sql_get_operators as $p_keys => $p_values)
                 {                 
		  $sql_get_operators[$p_keys]['all_persons_chats'] = $this->dashboard_model->get_all_persons_id($p_values['person_id']); 
                 }
                 
                 foreach($sql_get_operators as $operators){

                  echo "<tr>
                        <td class='thick-line'></td>
                        <td class='thick-line'></td>
                        <td class='thick-line text-center'>".$operators['first_name'] ."&nbsp;". $operators['last_name']."</td>
                        <td class='thick-line text-right'>".$operators['all_persons_chats']."</td>
                        </tr>";          
                }
               
                
                 // საშუალოდ ოპერატორზე გადანაწილებული ვიზიტორთა რაოდენობა
                $sashualo_visitori_operatorze = ( $all_chats / count($sql_get_operators));
                 echo "<tr>
                        <td class='thick-line'>საშუალოდ ოპერატორზე გადანაწილებული ვიზიტორთა რაოდენობა</td>
                        <td class='thick-line'></td>
                        <td class='thick-line text-center'></td>
                        <td class='thick-line text-right'>".round($sashualo_visitori_operatorze)."</td>
                        </tr>";
                 // ცენზურის ფილტრით დაბლოკლი ვიზიტორების რაოდენობა
                 echo "<tr>
                        <td class='thick-line'>ცენზურის ფილტრით დაბლოკლი ვიზიტორების რაოდენობა</td>
                        <td class='thick-line'></td>
                        <td class='thick-line text-center'></td>
                        <td class='thick-line text-right'>0</td>
                        </tr>";
                 
                // ცენზურის ფილტრით დაბლოკლი ვიზიტორების რაოდენობა
                 $get_sql_banlist = $this->dashboard_model->get_count_banlist(1);
                 echo "<tr>
                        <td class='thick-line'>ოპერატორების მიერ დაბლოკილი ვიზიტორების რაოდენობა</td>
                        <td class='thick-line'></td>
                        <td class='thick-line text-center'></td>
                        <td class='thick-line text-right'>$get_sql_banlist</td>
                        </tr>";
                 
                 // ადმინისტრატორის მიერ დაბლოკილი ვიზიტორების რაოდენობა
                 $get_sql_banlist = $this->dashboard_model->get_count_banlist_admn(0);
                 echo "<tr>
                        <td class='thick-line'>ადმინისტრატორის მიერ დაბლოკილი ვიზიტორების რაოდენობა</td>
                        <td class='thick-line'></td>
                        <td class='thick-line text-center'></td>
                        <td class='thick-line text-right'>$get_sql_banlist</td>
                        </tr>";
               
                echo '</tbody>
                </table>';
        
        }
    }
   

    function logout(){
        redirect('logout');
    }
}

<?php

include_once('MysqliDb.php');

class users extends MysqliDb {

    public function __construct() {
        include(dirname(__FILE__) . "/conn.php");

        parent::__construct($conn);
    }

    public function get_book_data() {
 
            $datas = $this->get('library'); //contains an Array of all users   
        return $datas;
    }

    public function add_book_data($data){
        $id = $this->insert('library',$data);
        return $id;
    }

//     GET BUSINESS TYPE DATA
    public function get_business_type($business_type_id = NULL) {
        if (!empty($business_type_id)) {
            $coll = array("business_type_id", "business_type_name");
            $this->where("business_type_id", $business_type_id);
            $datas = $this->get('business_type', null, $coll);
        } else {
            $datas = $this->get('business_type'); //contains an Array of all users 
        }
        return $datas;
    }

//     GET LABOUR TYPE DATA
    public function get_labour_type($labour_type_id = NULL) {
        if (!empty($labour_type_id)) {
            $this->where('labour_type_id', $labour_type_id);
            $datas = $this->get('labour_type');
        } else {
            $datas = $this->get('labour_type'); //contains an Array of all users   
        }
        return $datas;
    }

    public function get_labour_by_search($labour_type = NULL) {
        if (!empty($labour_type)) {
            $datas = $this->rawQuery("SELECT * FROM labour_type WHERE labour_type_name LIKE '%" . $labour_type . "%'");
        }
        return $datas;
    }

    public function get_countries() {
        $datas = $this->get('countries');
        return $datas;
    }

// GET SATES DATA...............
    public function get_states($state_id = null) {
        $coll = array("id", "name");
        if (!empty($state_id)) {
            $this->where("id", $state_id);
            $datas = $this->get('states', null, $coll);
        } else {
            $this->where("country_id", 101);
            $datas = $this->get('states', null, $coll);
        }
        return $datas;
    }

    public function get_state_by_search($state_name = null) {

        if (!empty($state_name)) {
            $datas = $this->rawQuery("SELECT * FROM states WHERE name LIKE '%" . $state_name . "%'");
        }
        return $datas;
    }

    public function get_cities($state_id = null, $city_id = null) {

        $coll = array("id", "name");
        if (!empty($state_id)) {
            $this->where("state_id", $state_id);
            $datas = $this->get('cities', null, $coll);
        } elseif (!empty($city_id)) {
            $this->where("id", $city_id);
            $datas = $this->get('cities', null, $coll);
        } else {
            $datas = $this->get('cities');
        }
        return $datas;
    }

    /*
     * ------------------------------------------------
     * USER RELATED QUERY IS OCCURE.
     * -----------------------------------------------
     */

// REGISTER DIFFRENT TYPE USER
    public function add_user($insertdata, $listing_id = NULL) {

        if (!empty($listing_id)) {
            $this->where('listing_id', $listing_id);
            $id = $this->update('edeep_listing', $insertdata);
        } else {
            $id = $this->insert('edeep_listing', $insertdata);
        }
        return $id;
    }

    public function delete_user($user_id = NULL) {
        if (!empty($user_id)) {
            $this->where('listing_id', $user_id);
            $id = $this->delete('edeep_listing');
        }
        return $id;
    }

    public function get_unapproved_user_datas() {
        $datas = $this->rawQuery("SELECT * from `edeep_listing`where user_status=0 ORDER by listing_id DESC");
        return $datas;
    }

    public function get_user_datas($user_id = NULL) {
        if (!empty($user_id)) {
            $datas = $this->rawQuery("SELECT * from `edeep_listing`where listing_id='$user_id'");
        } else {
            $datas = $this->get('edeep_listing');
        }
        return $datas;
    }

    public function get_user_datas_by_name($business_type_id = NULL, $user_name = NULL) {
        if (!empty($user_name)) {
            $datas = $this->rawQuery("SELECT * FROM edeep_listing WHERE business_type_id = '$business_type_id' AND user_full_name LIKE '%" . $user_name . "%'");
        }
        return $datas;
    }

    public function get_user_data_by_create_at($business_type_id = NULL, $start_date = NULL, $end_date = NULL) {
        if (!empty($start_date)) {
            $datas = $this->rawQuery("SELECT * FROM edeep_listing WHERE business_type_id = '$business_type_id' AND create_at BETWEEN '$start_date' AND '$end_date' ORDER by listing_id DESC");
        }
        return $datas;
    }

    public function get_user_by_search_data($business_type_id = NULL, $state_id = NULL, $labour_type_id = NULL) {
        if (!empty($state_id)) {
            $datas = $this->rawQuery("SELECT * from `edeep_listing`where business_type_id = '$business_type_id' AND user_state_id='$state_id'");
        } elseif (!empty($labour_type_id)) {
            $datas = $this->rawQuery("SELECT * from `edeep_listing`where business_type_id = '$business_type_id' AND labour_type_id='$labour_type_id'");
        }
        return $datas;
    }

    /*
     * ------------------------------------------------
     * PROPERTY RELATED QUERY IS OCCURE.
     * -----------------------------------------------
     */

    public function add_property($insertdata, $property_id = NULL) {
        if (!empty($property_id)) {
            $this->where('property_id', $property_id);
            $id = $this->update('property_zone', $insertdata);
        } else {
            $id = $this->insert('property_zone', $insertdata);
        }
        return $id;
    }

    public function get_property_datas($property_seller_id = NULL, $state_id = NULL) {
        if (!empty($property_seller_id)) {
            $datas = $this->rawQuery("SELECT * from `property_zone`where status=1 AND property_seller_id='$property_seller_id' ORDER by property_id DESC");
//            $this->where('property_seller_id', $property_seller_id);
//            $datas = $this->get('property_zone');
        } elseif (!empty($state_id)) {
            $this->where('property_state_id', $state_id);
            $datas = $this->get('property_zone');
        } else {
            $datas = $this->rawQuery("SELECT * from `property_zone`where status=1 ORDER by property_id DESC");
        }
        return $datas;
    }

    public function get_unapproved_property_datas() {
        $datas = $this->rawQuery("SELECT * from `property_zone`where status=0 ORDER by property_id DESC");
        return $datas;
    }

    public function get_property_type_data($property_type_id = NULL) {
        if (!empty($property_type_id)) {
            $this->where('property_type_id', $property_type_id);
            $datas = $this->get('propery_type');
        } else {
            $datas = $this->get('propery_type');
        }
        return $datas;
    }

    public function get_property_type_by_search($propert_type = NULL) {
        if (!empty($propert_type)) {
            $datas = $this->rawQuery("SELECT * FROM propery_type WHERE property_type_name LIKE '%" . $propert_type . "%'");
        }
        return $datas;
    }

    public function get_property_data_by_id($property_id = NULL, $property_type_id = NULL) {
        if (!empty($property_id)) {
            $this->where('property_id', $property_id);
            $datas = $this->get('property_zone');
        } elseif (!empty($property_type_id)) {
            $this->where('property_type_id', $property_type_id);
            $datas = $this->get('property_zone');
        } else {
            $datas = $this->get('property_zone');
        }
        return $datas;
    }

    public function get_diffrent_type_total_property($property_type_id, $property_seller_id) {
        if (!empty($property_type_id)) {
            $datas = $this->rawQuery("SELECT count(*) as total from `property_zone`where property_type_id = '$property_type_id' AND property_seller_id='$property_seller_id' ORDER by property_id DESC");
        }
        return $datas;
    }

    /*
     * ------------------------------------------------
     * LABOUR RELATED QUERY IS OCCURE.
     * -----------------------------------------------
     */

    public function add_labour_extra_info($insertdata, $labour_id = NULL) {
        if (!empty($labour_id)) {
            $this->where('labour_id', $labour_id);
            $id = $this->update('labour_extra_info', $insertdata);
        } else {
            $id = $this->insert('labour_extra_info', $insertdata);
        }
        return $id;
    }

    public function get_labour_extra_info($labour_id = NULL) {
        if (!empty($labour_id)) {
            $this->where('labour_id', $labour_id);
            $data = $this->get('labour_extra_info');
        } else {
            $data = $this->get('labour_extra_info');
        }
        return $data;
    }

    public function get_user_type_datas($business_type_id = NULL, $start_page = NULL, $end_page = NULL) {
        if (!empty($business_type_id)) {
            $datas = $this->rawQuery("SELECT * from `edeep_listing` where business_type_id = '$business_type_id' AND user_status =1 ORDER by listing_id DESC LIMIT $start_page,$end_page");
        }
        return $datas;
    }

    public function user_page_count($business_type_id = NULL, $page_quanity = NULL) {
        if (!empty($business_type_id)) {
            $count = $this->rawQuery("SELECT count(*)as total from `edeep_listing` where business_type_id = '$business_type_id' AND user_status =1");
        }

        $n = $count[0]['total'] / $page_quanity;
        $n = ceil($n);
        return $n;
    }

    /*
     * ------------------------------------------------
     * BUILDER RELATED QUERY IS OCCURE.
     * -----------------------------------------------
     */

    public function get_builder_datas($business_type_id = NULL) {
        if (!empty($business_type_id)) {
            $datas = $this->rawQuery("SELECT * from `edeep_listing` where business_type_id = '$business_type_id' AND user_status =1 ORDER by listing_id DESC");
        }
        return $datas;
    }

    public function add_builder_materials($insertdata, $builder_id = NULL) {
        if (!empty($builder_id)) {
            $this->where('builder_id', $builder_id);
            $id = $this->update('builder_materials', $insertdata);
        } else {
            $id = $this->insert('builder_materials', $insertdata);
        }
        return $id;
    }

    public function get_builder_materials($builder_id = NULL) {
        if (!empty($builder_id)) {
            $datas = $this->rawQuery("SELECT * from `builder_materials`where builder_id = '$builder_id'");
        } else {
            $datas = $this->get('builder_materials');
        }
        return $datas;
    }

    /*
     * ------------------------------------------------
     * CUSTOME QUERY IS OCCURE.
     * -----------------------------------------------
     */

    public function email_Exists($email_id) {
        $user_datas = $this->get_user_datas();

        foreach ($user_datas as $each_data) {
            if ($email_id == $each_data['user_email']) {
                $count = 1;
            } else {
                $count = 0;
            }
        }

        return $count;
    }

    /*
     * ------------------------------------------------
     * CART RELATED QUERY IS OCCURE.
     * -----------------------------------------------
     */

    public function add_to_cart($insertdata, $session_id = NULL) {
        if (!empty($session_id)) {
            $this->where('session_id', $session_id);
            $id = $this->update('add_to_cart', $insertdata);
        } else {
            $id = $this->insert('add_to_cart', $insertdata);
        }
        return $id;
    }

    public function delete_cart($user_id = NULL) {
        if (!empty($user_id)) {
            $this->where('user_id', $user_id);
            $id = $this->delete('add_to_cart');
        }
        return $id;
    }

    public function booked_user($insertdata) {
        $id = $this->insert('book_user', $insertdata);
        return $id;
    }

    public function get_booked_user($start_date, $end_date) {
        if (!empty($start_date) && !empty($end_date)) {
            $datas = $this->rawQuery("SELECT * FROM book_user WHERE create_at BETWEEN '$start_date' AND '$end_date' ORDER by book_user_id DESC");
        }
        return $datas;
    }

    public function get_cart_data($session_id) {
        if (!empty($session_id)) {
            $data = $this->rawQuery("SELECT * FROM add_to_cart WHERE session_id='$session_id'");
        }
        return $data;
    }

    public function get_count_cart_result($session_id) {
        if (!empty($session_id)) {
            $result = $this->rawQuery("SELECT COUNT(*) as total FROM add_to_cart WHERE session_id='$session_id'");
        }
        return $result;
    }

    public function user_id_exists($session_id, $user_id) {
        if (!empty($session_id) && !empty($user_id)) {
            $result = $this->rawQuery("SELECT COUNT(*) as total FROM add_to_cart WHERE session_id='$session_id' AND user_id='$user_id'");
        }
        return $result;
    }

    /*
     * ------------------------------------------------
     * ADMIN RELATED QUERY IS OCCURE.
     * -----------------------------------------------
     */

    public function add_admin($insertdata, $admin_id = NULL) {
        if (!empty($listing_id)) {
            $this->where('admin_id', $admin_id);
            $id = $this->update('admin', $insertdata);
        } else {
            $id = $this->insert('admin', $insertdata);
        }
        return $id;
    }

    public function get_login($password, $email_id) {
        $datas = $this->rawQuery("SELECT * from `edeep_listing`where user_email = '$email_id' AND user_password = '$password'");
        return $datas;
    }

    public function get_admin_login($password, $email_id) {
        $datas = $this->rawQuery("SELECT * from `admin`where admin_email = '$email_id' AND admin_password = '$password'");
        return $datas;
    }

    public function get_diffrent_type_total_user($business_type_id) {
        if (!empty($business_type_id)) {
            $datas = $this->rawQuery("SELECT count(*) as total from `edeep_listing`where business_type_id = '$business_type_id' AND user_status=1 ORDER by listing_id DESC");
        }
        return $datas;
    }

    public function get_total_lead() {
        $datas = $this->rawQuery("SELECT count(*) as total from `book_user`where status=1 ORDER by book_user_id DESC");
        return $datas;
    }

    public function add_email_data($insertdata) {
        $id = $this->insert('email_chat', $insertdata);
        return$id;
    }

    public function get_email_data($email_chat_id = NULL, $receiver_email_id = NULL) {
        if (!empty($receiver_email_id)) {
            $this->where('receiver_email_id', $receiver_email_id);
            $datas = $this->get('email_chat');
        } elseif (!empty($email_chat_id)) {
            $this->where('email_chat_id', $email_chat_id);
            $datas = $this->get('email_chat');
        } else {
            $datas = $this->get('email_chat');
        }
        return $datas;
    }

    public function search_email($sender_email_id) {
        if (!empty($sender_email_id)) {
            $data = $this->rawQuery("SELECT * FROM email_chat WHERE status = '1' AND sender_email_id LIKE '%" . $sender_email_id . "%'");
        }
        return $data;
    }

    public function get_labour_filter_id($filter_type = NULL) {
        if ($filter_type == 'low_to_high_price') {
            $data = $this->rawQuery("SELECT labour_id  FROM `labour_extra_info` ORDER BY labour_price_per_day ASC");
        } else {
            $data = $this->rawQuery("SELECT labour_id  FROM `labour_extra_info` ORDER BY labour_exprience ASC");
        }
        return $data;
    }

    public function get_builder_filter_id($filter_type = NULL) {
        if ($filter_type == 'low_to_high_price') {
            $data = $this->rawQuery("SELECT builder_id  FROM `builder_materials` ORDER BY builder_price_per_day ASC");
        } else {
            $data = $this->rawQuery("SELECT builder_id  FROM `builder_materials` ORDER BY builder_price_per_day DESC");
        }
        return $data;
    }

    public function get_property_filter_id($filter_type = NULL) {
        if ($filter_type == 'low_to_high_price') {
            $data = $this->rawQuery("SELECT *  FROM `property_zone` ORDER BY property_prices ASC");
        } elseif ($filter_type == 'low_to_high_floors') {
            $data = $this->rawQuery("SELECT *  FROM `property_zone` ORDER BY no_of_floors ASC");
        } elseif ($filter_type == 'low_to_high_bedrooms') {
            $data = $this->rawQuery("SELECT * FROM `property_zone` ORDER BY no_of_bedrooms ASC");
        } elseif ($filter_type == 'low_to_high_years_old') {
            $data = $this->rawQuery("SELECT *  FROM `property_zone` ORDER BY no_of_years_old ASC");
        }
        return $data;
    }

    public function get_labour_filter_user_data($get_data_by_filter) {
        if (is_array($get_data_by_filter) && count($get_data_by_filter) > 0) {
            foreach ($get_data_by_filter as $each_data) {
                $get_user_datas[] = $this->get_user_datas($each_data['labour_id']);
            }
            return $get_user_datas;
        }
    }

    public function get_builder_filter_user_data($get_data_by_filter) {
        if (is_array($get_data_by_filter) && count($get_data_by_filter) > 0) {
            foreach ($get_data_by_filter as $each_data) {
                $get_user_datas[] = $this->get_user_datas($each_data['builder_id']);
            }
            return $get_user_datas;
        }
    }

    public function get_property_filter_user_data($get_data_by_filter) {
        if (is_array($get_data_by_filter) && count($get_data_by_filter) > 0) {
            foreach ($get_data_by_filter as $each_data) {
                $get_user_datas[] = $this->get_property_data_by_id($each_data['property_id'], '');
            }
            return $get_user_datas;
        }
    }

}

?>

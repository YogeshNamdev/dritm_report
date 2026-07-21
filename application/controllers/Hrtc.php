<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Hrtc extends CI_Controller
{

    public function __construct()
    {
        
        parent::__construct();

        $this->load->model('Hrtc_model');
        $this->load->helper(array('url','form'));
    }

    /*
    |--------------------------------------------------------------------------
    | Search Page
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        
        $data['title'] = "HRTC Route Search";
        $data['stops'] = $this->Hrtc_model->getAllStops();

        $this->load->view('hrtc/index',$data);
    }

    /*
    |--------------------------------------------------------------------------
    | Search Bus
    |--------------------------------------------------------------------------
    */

    public function search()
    {

        $source = $this->input->post('source');
        $destination = $this->input->post('destination');

        if(empty($source))
        {
            echo json_encode(array(

                'status'=>false,
                'message'=>'Please Select Source'

            ));
            return;
        }

        if(empty($destination))
        {
            echo json_encode(array(

                'status'=>false,
                'message'=>'Please Select Destination'

            ));
            return;
        }

        if($source==$destination)
        {
            echo json_encode(array(

                'status'=>false,
                'message'=>'Source and Destination cannot be same.'

            ));
            return;
        }

        $result=$this->Hrtc_model->searchRoutes($source,$destination);

        echo json_encode(array(

            'status'=>true,
            'data'=>$result

        ));

    }

    /*
    |--------------------------------------------------------------------------
    | View Route
    |--------------------------------------------------------------------------
    */

    // public function route($routeId)
    // {

    //     $data['bus']=$this->Hrtc_model->getBusDetails($routeId);

    //     $data['route']=$this->Hrtc_model->getRouteDetails($routeId);

    //     $data['timeline']=$this->Hrtc_model->getRouteTimeline($routeId);

    //     $this->load->view('hrtc/route',$data);

    // }

    public function route($routeId)
{
    $source = $this->input->get('source');
    $destination = $this->input->get('destination');

    $data['bus'] = $this->Hrtc_model->getBusDetails($routeId);

    $data['route'] = $this->Hrtc_model->getRouteDetails($routeId);

    // Sirf Source se Destination tak ke stops
    $data['timeline'] = $this->Hrtc_model->getJourneyTimeline(
        $routeId,
        $source,
        $destination
    );

    $data['sourceStopId'] = $source;
    $data['destinationStopId'] = $destination;

    $this->load->view('hrtc/route', $data);
}

    /*
    |--------------------------------------------------------------------------
    | Get Journey Timeline
    |--------------------------------------------------------------------------
    */

    public function journey()
    {

        $routeId=$this->input->post('routeId');

        $source=$this->input->post('source');

        $destination=$this->input->post('destination');

        $timeline=$this->Hrtc_model->getJourneyTimeline(

            $routeId,
            $source,
            $destination

        );

        echo json_encode($timeline);

    }

    /*
    |--------------------------------------------------------------------------
    | Stop Autocomplete
    |--------------------------------------------------------------------------
    */

    public function autocompleteStops()
    {

        $keyword=$this->input->get('term');

        $result=$this->Hrtc_model->searchStop($keyword);

        $data=array();

        foreach($result as $row)
        {

            $data[]=array(

                'id'=>$row->StopId,

                'text'=>$row->StopName

            );

        }

        echo json_encode($data);

    }

    /*
    |--------------------------------------------------------------------------
    | Route Details AJAX
    |--------------------------------------------------------------------------
    */

    public function routeDetails()
    {

        $routeId=$this->input->post('routeId');

        $details=$this->Hrtc_model->getRouteDetails($routeId);

        echo json_encode($details);

    }

    /*
    |--------------------------------------------------------------------------
    | Timeline AJAX
    |--------------------------------------------------------------------------
    */

    public function timeline()
    {

        $routeId=$this->input->post('routeId');

        $timeline=$this->Hrtc_model->getRouteTimeline($routeId);

        echo json_encode($timeline);

    }

}
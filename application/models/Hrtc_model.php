<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Hrtc_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    /*
    |--------------------------------------------------------------------------
    | Get All Stops
    |--------------------------------------------------------------------------
    */

    public function getAllStops()
    {
        return $this->db
            ->select('StopId, StopName')
            ->from('hrtc_stops')
            ->where('IsActive',1)
            ->order_by('StopName','ASC')
            ->get()
            ->result();
    }

    /*
    |--------------------------------------------------------------------------
    | Search Stop (Select2 Autocomplete)
    |--------------------------------------------------------------------------
    */

    public function searchStop($keyword)
    {
        return $this->db
            ->select('StopId, StopName')
            ->from('hrtc_stops')
            ->like('StopName',$keyword)
            ->where('IsActive',1)
            ->order_by('StopName','ASC')
            ->limit(20)
            ->get()
            ->result();
    }

    /*
    |--------------------------------------------------------------------------
    | Get Stop Details
    |--------------------------------------------------------------------------
    */

    public function getStopById($stopId)
    {
        return $this->db
            ->where('StopId',$stopId)
            ->get('hrtc_stops')
            ->row();
    }

    /*
    |--------------------------------------------------------------------------
    | Search Routes
    |--------------------------------------------------------------------------
    */

    public function searchRoutes($sourceStopId,$destinationStopId)
    {

        $this->db->select('

            r.RouteId,
            r.DatabaseId,
            r.BatchRoute,
            r.TransportName,
            r.ServiceNo,
            r.DepotName,
            r.BusType,
            r.DepartureTime,
            r.ArrivalTime,
            r.TimeTaken,

            ss.StopName AS SourceName,
            ds.StopName AS DestinationName,

            rs.SourceOrder,
            rs.DestinationOrder,

            (rs.DestinationOrder-rs.SourceOrder) AS TotalStops

        ');

        $this->db->from('hrtc_route_search rs');

        $this->db->join(
            'hrtc_routes r',
            'r.RouteId=rs.RouteId'
        );

        $this->db->join(
            'hrtc_stops ss',
            'ss.StopId=rs.SourceStopId'
        );

        $this->db->join(
            'hrtc_stops ds',
            'ds.StopId=rs.DestinationStopId'
        );

        $this->db->where('rs.SourceStopId',$sourceStopId);

        $this->db->where('rs.DestinationStopId',$destinationStopId);

        $this->db->where('r.IsActive',1);

        $this->db->order_by('r.DepartureTime','ASC');

        return $this->db->get()->result();

    }

    /*
    |--------------------------------------------------------------------------
    | Bus Details
    |--------------------------------------------------------------------------
    */

    public function getBusDetails($routeId)
    {

        return $this->db

            ->where('RouteId',$routeId)

            ->get('hrtc_routes')

            ->row();

    }

    /*
    |--------------------------------------------------------------------------
    | Route Timeline
    |--------------------------------------------------------------------------
    */

    public function getRouteTimeline($routeId)
    {

        $this->db->select('

            rs.RouteStopId,

            rs.StopOrder,

            s.StopName,

            rst.ArrivalTime,

            rst.DepartureTime,

            rs.DistanceFromStart

        ');

        $this->db->from('hrtc_route_stops rs');

        $this->db->join(

            'hrtc_stops s',

            's.StopId=rs.StopId'

        );

        $this->db->join(

            'hrtc_route_stop_time rst',

            'rst.RouteStopId=rs.RouteStopId',

            'left'

        );

        $this->db->where(

            'rs.RouteId',

            $routeId

        );

        $this->db->order_by(

            'rs.StopOrder',

            'ASC'

        );

        return $this->db->get()->result();

    }

    /*
    |--------------------------------------------------------------------------
    | Route Details
    |--------------------------------------------------------------------------
    */

    public function getRouteDetails($routeId)
    {

        $this->db->select('

            r.*,

            COUNT(rs.RouteStopId) AS TotalStops

        ');

        $this->db->from('hrtc_routes r');

        $this->db->join(

            'hrtc_route_stops rs',

            'rs.RouteId=r.RouteId',

            'left'

        );

        $this->db->where(

            'r.RouteId',

            $routeId

        );

        $this->db->group_by('r.RouteId');

        return $this->db->get()->row();

    }

    /*
    |--------------------------------------------------------------------------
    | Route Stops
    |--------------------------------------------------------------------------
    */

    public function getRouteStops($routeId)
    {

        return $this->db

            ->select('

                rs.StopOrder,

                s.StopName

            ')

            ->from('hrtc_route_stops rs')

            ->join(

                'hrtc_stops s',

                's.StopId=rs.StopId'

            )

            ->where(

                'rs.RouteId',

                $routeId

            )

            ->order_by(

                'rs.StopOrder',

                'ASC'

            )

            ->get()

            ->result();

    }

    /*
    |--------------------------------------------------------------------------
    | Get Route Between Source & Destination
    |--------------------------------------------------------------------------
    */

    public function getJourneyTimeline($routeId,$sourceStopId,$destinationStopId)
    {

        $this->db->select('

            rs.StopOrder,

            s.StopName,

            rst.ArrivalTime,

            rst.DepartureTime

        ');

        $this->db->from('hrtc_route_stops rs');

        $this->db->join(

            'hrtc_stops s',

            's.StopId=rs.StopId'

        );

        $this->db->join(

            'hrtc_route_stop_time rst',

            'rst.RouteStopId=rs.RouteStopId',

            'left'

        );

        $this->db->where('rs.RouteId',$routeId);

        $this->db->where('rs.StopOrder >= (

            SELECT SourceOrder

            FROM hrtc_route_search

            WHERE RouteId='.$this->db->escape($routeId).'

            AND SourceStopId='.$this->db->escape($sourceStopId).'

            AND DestinationStopId='.$this->db->escape($destinationStopId).'

            LIMIT 1

        )',NULL,FALSE);

        $this->db->where('rs.StopOrder <= (

            SELECT DestinationOrder

            FROM hrtc_route_search

            WHERE RouteId='.$this->db->escape($routeId).'

            AND SourceStopId='.$this->db->escape($sourceStopId).'

            AND DestinationStopId='.$this->db->escape($destinationStopId).'

            LIMIT 1

        )',NULL,FALSE);

        $this->db->order_by('rs.StopOrder','ASC');

        return $this->db->get()->result();

    }

}
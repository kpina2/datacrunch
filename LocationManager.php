<?php
  class LocationManager extends DBconnection{
    public $range_from = 0;
    public $locationtable = "marker";
    public function __construct(){
      parent::__construct();
    }

    function findMarkers($coords, $range_to, $tag_id, $limit=null){
      $limitclause = "";
      if(!empty($limit) && is_numeric($limit))
      {
         $limitclause = "LIMIT " . $limit;
      }
      $lat = $coords->lat;
      $lon = $coords->lon;
      $range_from = $this->range_from;
      $locationtable = $this->locationtable;
      $tagjoin = ""; $tagwhere = "";
      if(!empty($tag_id) && is_numeric($tag_id) && $tag_id >0){
        $tagjoin = "JOIN marker_tag ON z.id = marker_tag.marker_id ";
        $tagwhere = "AND marker_tag.tag_id = $tag_id ";
      }

      $sql = "SELECT 3956 * 2 * ATAN2(SQRT(POW(SIN((RADIANS($lat) - "
        .'RADIANS(z.lat)) / 2), 2) + COS(RADIANS(z.lat)) * '
        ."COS(RADIANS($lat)) * POW(SIN((RADIANS($lon) - "
        ."RADIANS(z.lon)) / 2), 2)), SQRT(1 - POW(SIN((RADIANS($lat) - "
        ."RADIANS(z.lat)) / 2), 2) + COS(RADIANS(z.lat)) * "
        ."COS(RADIANS($lat)) * POW(SIN((RADIANS($lon) - "
        ."RADIANS(z.lon)) / 2), 2))) AS \"miles\", z.* FROM $locationtable z "
        ."$tagjoin"
        ."WHERE lat BETWEEN ROUND($lat - (25 / 69.172), 4) "
        ."AND ROUND($lat + (25 / 69.172), 4) "
        ."AND lon BETWEEN ROUND($lon - ABS(25 / COS($lat) * 69.172)) "
        ."AND ROUND($lon + ABS(25 / COS($lat) * 69.172)) "
        ."AND 3956 * 2 * ATAN2(SQRT(POW(SIN((RADIANS($lat) - "
        ."RADIANS(z.lat)) / 2), 2) + COS(RADIANS(z.lat)) * "
        ."COS(RADIANS($lat)) * POW(SIN((RADIANS($lon) - "
        ."RADIANS(z.lon)) / 2), 2)), SQRT(1 - POW(SIN((RADIANS($lat) - "
        ."RADIANS(z.lat)) / 2), 2) + COS(RADIANS(z.lat)) * "
        ."COS(RADIANS($lat)) * POW(SIN((RADIANS($lon) - "
        ."RADIANS(z.lon)) / 2), 2))) <= $range_to "
        ."AND 3956 * 2 * ATAN2(SQRT(POW(SIN((RADIANS($lat) - "
        ."RADIANS(z.lat)) / 2), 2) + COS(RADIANS(z.lat)) * "
        ."COS(RADIANS($lat)) * POW(SIN((RADIANS($lon) - "
        ."RADIANS(z.lon)) / 2), 2)), SQRT(1 - POW(SIN((RADIANS($lat) - "
        ."RADIANS(z.lat)) / 2), 2) + COS(RADIANS(z.lat)) * "
        ."COS(RADIANS($lat)) * POW(SIN((RADIANS($lon) - "
        ."RADIANS(z.lon)) / 2), 2))) >= $range_from "
        . "$tagwhere"
        ."ORDER BY 1 ASC $limitclause";
        
      $stmt = $this->pdo->query($sql);
      return $stmt->fetchAll(PDO::FETCH_ASSOC);
      
    }
  }
?>
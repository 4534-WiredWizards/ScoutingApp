<?php

$output = array(
   "fields" => array(
      "score" => 50,
      "questions" => array(
         array(
            "before" => "<hr><b><h3>Defenses</h3></b>",
         ),
         array(
            "field" => "Can go through Portcullis",
            "value" => false,
            "type" => "checkbox",
         ),
         array(
            "field" => "Can go over Cheval de Frise",
            "value" => false,
            "type" => "checkbox",
         ),
         array(
            "field" => "Can go over Ramparts",
            "value" => false,
            "type" => "checkbox",
         ),
         array(
            "field" => "Can go over Moat",
            "value" => false,
            "type" => "checkbox",
         ),
         array(
            "field" => "Can go through Drawbridge",
            "value" => false,
            "type" => "checkbox",
         ),
         array(
            "field" => "Can go through Sally Port",
            "value" => false,
            "type" => "checkbox",
         ),
         array(
            "field" => "Can go over Rock Wall",
            "value" => false,
            "type" => "checkbox",
         ),
         array(
            "field" => "Can go Rough Terrain",
            "value" => false,
            "type" => "checkbox",
         ),
         array(
            "field" => "Can go through Low Bar",
            "value" => false,
            "type" => "checkbox",
         ),
         array(
            "before" => "<hr>",
         ),
         array(
            "field" => "Other Information",
            "value" => "",
            "type" => "textarea",
         ),
         // array(
         //    "field" => "",
         //    "value" => "",
         //    "type" => "",
         // ),
      ),
      "stats" => array(),
      "scores" => array()
   )
);

$output["fields"]["stats_json"] = json_encode($output["fields"]["stats"]);
$output["fields"]["scores_json"] = json_encode($output["fields"]["scores"]);
$output["fields"]["questions_json"] = json_encode($output["fields"]["questions"]);

<?php

$id = $_GET["id"];

$x1 = $mesto->data["x"];
$y1 = $mesto->data["y"];

$m = new mesto();
$m->nacti($id);
$x2 = $m->data["x"];
$y2 = $m->data["y"];

$pohyb = new pohyb();

echo json_encode($pohyb->cesta(intval($x1), intval($y1), intval($x2), intval($y2)));
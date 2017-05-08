<?php
$data = [
    "penize" => intval($user->penize),
    "surovina1" => intval($mesto->surovina1),
    "surovina2" => intval($mesto->surovina2),
    "surovina3" => intval($mesto->surovina3),
    "surovina4" => intval($mesto->surovina4),
    "surovina1_produkce" => intval($mesto->data["surovina1_produkce"]),
    "surovina2_produkce" => intval($mesto->data["surovina2_produkce"]),
    "surovina3_produkce" => intval($mesto->data["surovina3_produkce"]),
    "surovina4_produkce" => intval($mesto->data["surovina4_produkce"]),
    "sklad" => intval($mesto->data["sklad"]),
    "zpravy" => intval($user->data["zprava"]),
    "jednotky" => $mesto->jednotky(),
    "refresh" => $mesto->refresh_time()
];
echo json_encode($data);

?>
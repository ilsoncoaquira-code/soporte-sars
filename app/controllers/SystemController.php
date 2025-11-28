<?php

require_once "../app/models/SystemModel.php";

class SystemController {

    public function index() {

        $model = new SystemModel();
        $dbStatus = $model->checkDatabase();

        // Datos que se mandan a la vista
        $data = [
            "service" => "Soporte Sars",
            "database" => $dbStatus,
            "status" => $dbStatus === "connected" ? "ok" : "fail"
        ];

        // Cargar vista (MVC real)
        include "../app/views/systemStatusView.php";
    }
}

?>

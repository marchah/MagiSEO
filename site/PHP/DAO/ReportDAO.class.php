<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/MagiSEO/site/PHP/Object/Report.class.php';
@session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/MagiSEO/site/PHP/DAO/DAO.class.php';


class ReportDAO extends DAO {
   
    private static function createReport($data) {
        return new Report($data['id'], $data['iduser'], $data['login'], $data['title'], $data['description'], $data['type'], $data['date']);
    }

    static function insertReport($report) {
        $bdd = parent::ConnectionBDD();
        $req = $bdd->prepare("INSERT INTO reporting (iduser, title, description, type, date) VALUES(:iduser, :title, :description, :type, :date)");
        $req->execute([
                        'iduser' => $report->getIdUser(),
                        'title' => $report->getTitle(),
                        'description' => $report->getDescription(),
                        'type' => $report->getType(),
                        'date' => $report->getDate()
                ]);
        $report->setId($bdd->lastInsertId());
        return $report;
    }

    static function deleteReport($idReport) {
        $bdd = parent::ConnectionBDD();
        $req = $bdd->prepare("DELETE FROM reporting WHERE id=:id");
        $req->execute(['id' => $idReport]);
    }

    static function getAllReport() {
        $listReport = array();
        $bdd = parent::ConnectionBDD();
        $response = $bdd->query('SELECT l.*,  u.login FROM reporting l LEFT JOIN user u ON l.iduser = u.id ORDER BY l.id DESC');

        while ($data = $response->fetch()) {
            $listReport[] = self::createReport($data);
        }
        $response->closeCursor();
        return $listReport;
    }

    static function getReportByType($type) {
        $listReport = array();
        $bdd = parent::ConnectionBDD();
        $response = $bdd->query('SELECT l.*,  u.login FROM reporting l LEFT JOIN user u ON l.iduser = u.id WHERE l.type = ' . $type . ' ORDER BY l.id DESC LIMIT ' . LOG_NB_LIMIT);

        while ($data = $response->fetch()) {
            $listReport[] = self::createReport($data);
        }
        $response->closeCursor();
        return $listReport;
    }
    
    static function getNbServerErrorWarning() {
        $bdd = parent::ConnectionBDD();
        $response = $bdd->query('(SELECT COUNT(*) AS "All" FROM server_slave )'
                        . 'UNION ALL ( SELECT COUNT(id) AS "All" FROM reporting WHERE type = ' . REPORTING_TYPE_SLAVE_ERROR . ')'
                        . 'UNION ALL ( SELECT COUNT(id) AS "All" FROM reporting WHERE type = ' . REPORTING_TYPE_SLAVE_WARNING . ')');
        $i = 0;
        $anwser = "";
        while ($data = $response->fetch()) {
            if ($i != 0)
                    $anwser .= "/";
            $anwser .= $data['All'];
            $i++;
        }
        $response->closeCursor();
        return $anwser;
    }
}

?>
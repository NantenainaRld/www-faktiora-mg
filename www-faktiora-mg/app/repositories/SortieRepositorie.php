<?php

//CLASS - sortie repositorie
class SortieRepositorie extends Database
{

    public function __construct()
    {
        parent::__construct();
    }

    //static - connection sortie
    public static function connectionSortie($num = "")
    {
        $response = ['message_type' => 'success', 'message' => 'success'];
        $num = strtoupper(trim($num));
        $libelle = "correction/" . $num;
        $libelle = "%" . $libelle . "%";

        try {

            $response = parent::selectQuery(
                "SELECT ds.num_ds, a.libelle_article, ds.date_ds, lds.prix_article FROM demande_sortie ds JOIN ligne_ds lds ON lds.id_ds = ds.id_ds JOIN article a ON a.id_article = lds.id_article WHERE (a.libelle_article LIKE :libelle ) AND ds.etat_ds !='supprimé' AND a.etat_article != 'supprimé' ",
                ['libelle' => $libelle]
            );

            //error
            if ($response['message_type'] === 'error') {
                return $response;
            }

            return $response;
        } catch (Throwable $e) {
            error_log($e->getMessage() .
                ' - Line : ' . $e->getLine() .
                ' - File : ' . $e->getFile());

            $response = [
                'message_type' => 'error',
                'message' => __(
                    'errors.catch.sortie_connectionSortie',
                    ['field' => $e->getMessage() .
                        ' - Line : ' . $e->getLine() .
                        ' - File : ' . $e->getFile()]
                )
            ];

            return $response;
        }

        return $response;
    }
}

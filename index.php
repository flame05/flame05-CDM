<?php
session_start();
//$db = new mysqli("localhost", "cdm", "Ktts4%57", "flame-cdm");
$db = new mysqli("localhost", "root", "", "flame-cdm");

function autoLoadYo($class) {
    include 'php/class/' . $class . '.class.php';
}

spl_autoload_register('autoLoadYo');


$admin = array();
$admin[1] = 'Operatore';
$admin[2] = 'Capo Operatore';
$admin[5] = 'Logistica';
$admin[9] = 'Admin';
$cdm_mess = false;
$user = new user();
$pagina = 'home';
if(!empty($_GET['page']))
{
    $pagina = $_GET['page'];
    if($pagina == 'login')
    {
        if(!empty($_POST['cdm-user']))
        {
            if($user->login($_POST['cdm-user'],$_POST['cdm-pass']))
            {
                header("Location: index.php");
            exit;
            }

        }
    }
    if($pagina == 'logout')
    {
        $user->logout();
        header("Location: index.php");
    exit;
    }
}

if(isset($_GET['update'])){
    $utente = new utente();
    $arr = $utente->loadUser($_GET['id']);
    echo json_encode($arr);
    exit;
}

if(isset($_GET['parteupdate'])){
    $parte = new parte();
    $arr = $parte->loadParte($_GET['id']);
    echo json_encode($arr);
    exit;
}

if(isset($_GET['sposta'])){
    $articolo = new articolo();
    if(isset($_SESSION['indietro']))
      $articolo->sposta($_GET['sposta'],1);
    else
      $articolo->sposta($_GET['sposta']);
header("Location: index.php");
    exit;
}

if(isset($_GET['addArticolo'])){
    $articolo = new articolo();
    $articolo->crea($_POST['add_id_modello'],$_POST['add_id_parte'],$_POST['add_pos_madre'],$_POST['add_posizione'],$_POST['add_nome'],$_POST['add_colore']);
header("Location: index.php");
    exit;
}

if(isset($_GET['indietro']))
{
  if(isset($_SESSION['indietro']))
    unset($_SESSION['indietro']);
  else
    $_SESSION['indietro'] = true;
header("Location: index.php");
    exit;
}

if(isset($_GET['aggiungere']))
{
  if(isset($_SESSION['aggiungere']))
    unset($_SESSION['aggiungere']);
  else
    $_SESSION['aggiungere'] = true;
header("Location: index.php");
    exit;
}


?>




<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="Applicazione per catena di montaggio">
<meta name="author" content="Daniel Bernasconi">

<title>Demo App</title>
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" integrity="sha512-dTfge/zgoMYpP7QbHy4gWMEGsbsdZeCXz7irItjcC3sPUFtf0kuFbDz/ixG7ArTxmDjLXDmezHubeNikyKGVyQ==" crossorigin="anonymous">

<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css" integrity="sha384-aUGj/X2zp5rLCbBxumKTCw2Z50WgIr1vs/PFN4praOTvYXWlVyh2UtNUU0KAUhAX" crossorigin="anonymous">

<script src="http://code.jquery.com/jquery-2.1.4.min.js"></script>
<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js" integrity="sha512-K1qjQ+NcF2TYO/eI3M6v8EiNYZfA95pQumfvcVrTHtwQVDG+aHRqLi/ETn2uB+1JqwYqVG3LIvdm9lj6imS/pQ==" crossorigin="anonymous"></script>
</head>


<body>
<?php
if($pagina == 'admin'){
 ?>
<div class="modal fade" id="modUtente" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Modifica utente</h4>
      </div>
      <div class="modal-body">
         <form action="index.php?page=admin&subpage=users&method=mod" id="modForm" method="POST">
         <input type="text" id="mod_id" name="mod_id" style="visibility:hidden;">
         <input type="text" id="mod_zen" name="mod_zen" value="1" style="visibility:hidden;">
      <div class="form-group">
        <label>Nome</label>
        <input type="text" id="mod_nome" name="mod_nome" class="form-control">
      </div>
      <div class="form-group">
        <label>Username</label>
        <input type="text" id="mod_username" name="mod_username" class="form-control">
      </div>
      <div class="form-group">
        <label>Password</label>
        <input type="text" id="mod_password" name="mod_password" class="form-control">
      </div>
      <div class="form-group">
        <label>Livello</label>
        <select id="mod_livello" name="mod_livello" class="form-control">
            <?php
            foreach($admin as $n => $nn)
                echo '<option value="'.$n.'">'.$nn.'</option>';
            ?>
        </select>
      </div>
    </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" id="modErase">Elimina utente</button>
        <button type="button" class="btn btn-primary" id="modSubmit">Salva modifiche</button>
      </div>
    </div>
  </div>
</div>

        <?php
        if(isset($_GET['subpage']))
        {
                switch($_GET['subpage'])
                {


                    case 'model':
                ?>
                <div class="modal fade" id="modParte" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                  <div class="modal-dialog" role="document">
                    <div class="modal-content">
                      <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">Modifica parte</h4>
                      </div>
                      <div class="modal-body">
                         <form action="index.php?page=admin&subpage=model&id=<?php echo $_GET['id']; ?>" id="modPForm" method="POST">
                         <input type="text" id="modPid" name="modPid" style="visibility:hidden;">
                      <div class="form-group">
                        <label>Nome</label>
                        <input type="text" id="modPnome" name="modPnome" class="form-control">
                      </div>
                      <div class="form-group">
                        <label>Ordine</label>
                        <input type="text" id="modPordine" name="modPordine" class="form-control">
                      </div>
                    </form>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-primary" id="modPSubmit">Salva modifiche</button>
                      </div>
                    </div>
                  </div>
                </div>
                <?php 
                break;

             }

        } ?>
 <?php } ?>

 <div class="modal fade" id="addArticolo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Aggiungi Articolo</h4>
      </div>
      <div class="modal-body">
         <form action="index.php?addArticolo" id="addForm" method="POST">
         <input type="text" id="add_pos_madre" name="add_pos_madre" style="visibility:hidden;">
         <input type="text" id="add_id_modello" name="add_id_modello" style="visibility:hidden;">
         <input type="text" id="add_posizione" name="add_posizione" style="visibility:hidden;">
         <input type="text" id="add_id_parte" name="add_id_parte" style="visibility:hidden;">
      <div class="form-group">
        <label>Nome</label>
        <input type="text" id="add_nome" name="add_nome" class="form-control">
      </div>
      <div class="form-group">
        <label>Colore</label>
        <input type="text" id="add_colore" name="add_colore" value="btn-primary" class="form-control">
      </div>
    </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" id="addArticle">Aggiungi</button>
      </div>
    </div>
  </div>
</div>



    <nav class="navbar navbar-default navbar-fixed-top" style="width:100%;">
      <div class="container" style="width:100%;">
        <div class="navbar-header">
          <a class="navbar-brand" style="background-color: #2b669a;color:#FFF; " href="javascript:void(0);">CDM</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav">


            <?php if($user->online) { ?><li<?php if($pagina == 'home'){ ?> class="active"<?php } ?>><a href="index.php">Catena di Montaggio</a></li><?php } ?>
            <?php if($user->l > 4) { ?><li<?php if($pagina == 'admin'){ ?> class="active"<?php } ?>><a href="index.php?page=admin">Amministrazione</a></li><?php } ?>




            <?php if($user->online) { ?><li><a href="index.php?page=logout">Logout</a></li><?php } ?>
            <?php if($user->offline){ ?><li><a href="javascript:void(0);">Login</a></li> <?php } ?>
            <?php if($user->l > 1) { ?><li<?php if(isset($_SESSION['indietro'])){ ?> class="active"<?php } ?>><a href="index.php?indietro"><-</a></li><?php } ?>
            <?php if($user->l > 4) { ?><li<?php if(isset($_SESSION['aggiungere'])){ ?> class="active"<?php } ?>><a href="index.php?aggiungere">+</a></li><?php } ?>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>

    
    <?php if($user->offline){ ?>
    <div class="container" style="margin-top: 100px;">
         <div class="col-md-6"><h1>Login</h1>
        <form action="index.php?page=login" method="POST">
        <div class="form-group">
        <label>Username</label>
        <input type="text" class="form-control" name="cdm-user">
        </div>
        <div class="form-group">
        <label>Password</label>
        <input type="password" class="form-control" name="cdm-pass">
        </div>
        <button type="submit" class="btn btn-default">Accedi</button>
        </form>
        </div>
    </div>
    <?php } else{

        switch($pagina)
        {
            case 'admin':
            if(!empty($_GET['subpage']))
            {
                switch($_GET['subpage'])
                {
                    case 'cdm':
                    if(isset($_GET['erase_project']))
                    {
                        $id = $_GET['erase_project'];
                        $processo = new processo();
                        $processo->elimina($id);
                        $cdm_mess = '<div class="alert alert-success alert-dismissible fade in" role="alert">
                                                     <button type="button" class="close" data-dismiss="alert" aria-label="Chiudi"><span aria-hidden="true">×</span></button>
                                                     <strong>Progetto eliminato con successo!</strong></div>';
                    }
                    if(isset($_GET['add_project']))
                    {
                        $processo = new processo();
                        $processo->crea($_POST['nome']);
                        $cdm_mess = '<div class="alert alert-success alert-dismissible fade in" role="alert">
                                                     <button type="button" class="close" data-dismiss="alert" aria-label="Chiudi"><span aria-hidden="true">×</span></button>
                                                     <strong>Progetto '.$_POST['nome'].' creato con successo!</strong></div>';
                    }

                    ?>
                        <div class="container" style="margin-top: 100px;">
                            <div class="row" style="margin-top: 15px;">
                                <div class="col-md-3">&nbsp;</div>
                                <div class="col-md-6">
                                <?php
                                if($cdm_mess) echo $cdm_mess;
                                 ?>

                                 <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                                  <div class="panel panel-default">
                                    <div class="panel-heading" role="tab" id="headingOne">
                                      <h4 class="panel-title list-group-item active">
                                        <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                          Aggiungi Progetto
                                        </a>
                                      </h4>
                                    </div>
                                    <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
                                      <div class="panel-body">
                                        <form action="index.php?page=admin&subpage=cdm&add_project" method="POST">
                                          <div class="form-group">
                                            <label>Nome</label>
                                            <input type="text" name="nome" class="form-control">
                                          </div>
                                          <button type="submit" class="btn btn-default">Crea</button>
                                        </form>
                                      </div>
                                    </div>
                                  </div>
                                  <div class="panel panel-default">
                                    <div class="panel-heading" role="tab" id="headingTwo">
                                      <h4 class="panel-title list-group-item active">
                                        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                          Lista progetti
                                        </a>
                                      </h4>
                                    </div>
                                    <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
                                      <div class="panel-body">
                                        <div class="table-responsive">
                                          <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                 <th>ID</th>
                                                 <th>Nome</th>
                                                 <?php if($user->l > 5){ ?>
                                                 <th>Elimina</th>
                                                 <?php } ?>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                                $processo = new processo();
                                                $processi = $processo->getProcessi();
                                                foreach($processi as $key => $oprocesso)
                                                {

                                                    echo '<tr><td>'.$oprocesso->id.'</td><td><a href="index.php?page=admin&subpage=project&id='.$oprocesso->id.'">'.$oprocesso->nome.'</a></td>';
                                                    if($user->l > 5) echo '<td><a href="index.php?page=admin&subpage=cdm&erase_project='.$oprocesso->id.'"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></a></td>';
                                                    echo '</tr>';
                                                }
                                                
                                            ?>
                                            </tbody>
                                          </table>
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                    <?php
                    break;
                    case 'model':
                    $id_modello = $_GET['id'];

                    $parte = new parte();
                    $parti = $parte->getParti($id_modello);
                    $nome_modello = $parte->getModello($id_modello);


                    if(isset($_GET['add_parte']))
                    {
                        $parx = new parte();
                        $parx->crea($_POST['id'],$_POST['nome']);
                        $cdm_mess = '<div class="alert alert-success alert-dismissible fade in" role="alert">
                                     <button type="button" class="close" data-dismiss="alert" aria-label="Chiudi"><span aria-hidden="true">×</span></button>
                                     <strong>Parte '.$_POST['nome'].' aggiunto al modello '.$nome_modello.'!</strong></div>';
                        $parte = new parte();
                        $parti = $parte->getParti($id_modello);
                    }

                    if(isset($_GET['erase_part']))
                    {
                        $parx = new parte();
                        $parx->elimina($_GET['erase_part']);
                        $cdm_mess = '<div class="alert alert-success alert-dismissible fade in" role="alert">
                                     <button type="button" class="close" data-dismiss="alert" aria-label="Chiudi"><span aria-hidden="true">×</span></button>
                                     <strong>Parte eliminata dal modello '.$nome_modello.'!</strong></div>';
                        $parte = new parte();
                        $parti = $parte->getParti($id_modello);
                    }

                    if(isset($_POST['modPid']))
                    {
                        $parx = new parte();
                        $parx->aggiorna($_POST['modPid'],$_POST['modPnome'],$_POST['modPordine']);
                        $cdm_mess = '<div class="alert alert-success alert-dismissible fade in" role="alert">
                                     <button type="button" class="close" data-dismiss="alert" aria-label="Chiudi"><span aria-hidden="true">×</span></button>
                                     <strong>La parte '.$_POST['modPnome'].' è stata aggiornata con successo!</strong></div>';
                        $parte = new parte();
                        $parti = $parte->getParti($id_modello);
                    }

                    if(isset($_POST['add_lavori']))
                    {
                        $lavori = new lavori();
                        $lavori->crea($_POST['id'],$_POST['nome'],$_POST['colore']);
                        $cdm_mess = '<div class="alert alert-success alert-dismissible fade in" role="alert">
                                     <button type="button" class="close" data-dismiss="alert" aria-label="Chiudi"><span aria-hidden="true">×</span></button>
                                     <strong>La parte '.$_POST['modPnome'].' è stata aggiornata con successo!</strong></div>';
                    }

                    ?>


                         <div class="container" style="margin-top: 100px;">
                            <div class="row" style="margin-top: 15px;">
                                <div class="col-md-3">&nbsp;</div>
                                <div class="col-md-6">
                                <?php
                                if($cdm_mess) echo $cdm_mess;
                                 ?>


                                <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                                  <div class="panel panel-default">
                                    <div class="panel-heading" role="tab" id="headingOne">
                                      <h4 class="panel-title list-group-item active">
                                        <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                          Aggiungi Parte a <?php echo $nome_modello; ?>
                                        </a>
                                      </h4>
                                    </div>
                                    <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
                                      <div class="panel-body">
                                        <form action="index.php?page=admin&subpage=model&id=<?php echo $id_modello; ?>&add_parte" method="POST">
                                            <input type="text" name="id" value="<?php echo $id_modello; ?>" class="form-control" style="visibility:hidden;display:none;">
                                          <div class="form-group">
                                            <label>Nome</label>
                                            <input type="text" name="nome" class="form-control">
                                          </div>
                                          <button type="submit" class="btn btn-default">Aggiungi</button>
                                        </form>
                                      </div>
                                    </div>
                                  </div>
                                  <div class="panel panel-default">
                                    <div class="panel-heading" role="tab" id="headingTwo">
                                      <h4 class="panel-title list-group-item active">
                                        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                          Elenco parti nel modello <?php echo $nome_modello; ?>
                                        </a>
                                      </h4>
                                    </div>
                                    <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
                                      <div class="panel-body">
                                        <div class="table-responsive">
                                          <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                 <th>ID</th>
                                                 <th>Nome</th>
                                                 <th>Modifica</th>
                                                 <th>Elimina</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                                foreach($parti as $key => $oprocesso)
                                                {

                                                    echo '<tr><td>'.$oprocesso->id.'</td><td>'.$oprocesso->nome.'</td><td><a href="javascript:void(0);" data-parte-id="'.$oprocesso->id.'" class="cdm-mod-parte"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a></td><td><a href="index.php?page=admin&subpage=model&id='.$id_modello.'&erase_part='.$oprocesso->id.'"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></a></td>';
                                                    echo '</tr>';
                                                }
                                                
                                            ?>
                                            </tbody>
                                          </table>
                                        </div>
                                      </div>
                                    </div>
                                  </div>

                                  <div class="panel panel-default">
                                    <div class="panel-heading" role="tab" id="headingThree">
                                      <h4 class="panel-title list-group-item active">
                                        <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseThree" aria-expanded="true" aria-controls="collapseThree">
                                          Aggiungi Lavoro Principale a <?php echo $nome_modello; ?>
                                        </a>
                                      </h4>
                                    </div>
                                    <div id="collapseThree" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
                                      <div class="panel-body">
                                        <form action="index.php?page=admin&subpage=model&id=<?php echo $id_modello; ?>&add_lavori" method="POST">
                                            <input type="text" name="id" value="<?php echo $id_modello; ?>" class="form-control" style="visibility:hidden;display:none;">
                                          <div class="form-group">
                                            <label>Nome</label>
                                            <input type="text" name="nome" class="form-control">
                                          </div>
                                          <div class="form-group">
                                            <label>Colore (HEX: #******)</label>
                                            <input type="text" name="colore" class="form-control">
                                          </div>

                                          <button type="submit" class="btn btn-default">Aggiungi</button>
                                        </form>
                                      </div>
                                    </div>
                                  </div>
                                  <div class="panel panel-default">
                                    <div class="panel-heading" role="tab" id="headingFour">
                                      <h4 class="panel-title list-group-item active">
                                        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                                          Elenco lavori principali nel modello <?php echo $nome_modello; ?>
                                        </a>
                                      </h4>
                                    </div>
                                    <div id="collapseFour" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
                                      <div class="panel-body">
                                        <div class="table-responsive">
                                          <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                 <th>ID</th>
                                                 <th>Nome</th>
                                                 <th>Colore</th>
                                                 <th>Modifica</th>
                                                 <th>Elimina</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                                $lavori = new lavori();
                                                $lavx = $lavori->getLavori($id_modello);
                                                foreach($lavx as $key => $oprocesso)
                                                {

                                                    echo '<tr><td>'.$oprocesso->id.'</td><td><a href="index.php?page=admin&subpage=parti&id='.$oprocesso->id.'">'.$oprocesso->nome.'</a></td><td><span style="color:'.$oprocesso->colore.'">'.$oprocesso->colore.'</span></td><td><a href="javascript:void(0);" data-parte-id="'.$oprocesso->id.'" class="cdm-mod-parte"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a></td><td><a href="index.php?page=admin&subpage=model&id='.$id_modello.'&erase_part='.$oprocesso->id.'"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></a></td></tr>';
                                                    
                                                }
                                                
                                            ?>
                                            </tbody>
                                          </table>
                                        </div>
                                      </div>
                                    </div>
                                  </div>



                                </div>

                               </div>
                            </div>               
                        </div>


                    <?php
                    break;
                    case 'project':
                    $prox = new processo();
                    $processo = $prox->gPro($_GET['id']);


                    if(isset($_GET['add_modello']))
                    {
                        $modx = new modello();
                        $modx->crea($_POST['id'],$_POST['nome']);
                        $cdm_mess = '<div class="alert alert-success alert-dismissible fade in" role="alert">
                                     <button type="button" class="close" data-dismiss="alert" aria-label="Chiudi"><span aria-hidden="true">×</span></button>
                                     <strong>Modello '.$_POST['nome'].' aggiunto!</strong></div>';
                    }

                    if(isset($_GET['erase_model']))
                    {
                        $modx = new modello();
                        $modx->elimina($_GET['erase_model']);
                        $cdm_mess = '<div class="alert alert-success alert-dismissible fade in" role="alert">
                                     <button type="button" class="close" data-dismiss="alert" aria-label="Chiudi"><span aria-hidden="true">×</span></button>
                                     <strong>Il modello è stato eliminato!</strong></div>';
                    }
                    ?>


                        <div class="container" style="margin-top: 100px;">
                            <div class="row" style="margin-top: 15px;">
                                <div class="col-md-3">&nbsp;</div>
                                <div class="col-md-6">
                                <?php
                                if($cdm_mess) echo $cdm_mess;
                                 ?>


                                <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                                  <div class="panel panel-default">
                                    <div class="panel-heading" role="tab" id="headingOne">
                                      <h4 class="panel-title list-group-item active">
                                        <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                          Aggiungi Modello a <?php echo $processo->nome; ?>
                                        </a>
                                      </h4>
                                    </div>
                                    <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
                                      <div class="panel-body">
                                        <form action="index.php?page=admin&subpage=project&id=<?php echo $processo->id; ?>&add_modello" method="POST">
                                            <input type="text" name="id" value="<?php echo $processo->id; ?>" class="form-control" style="visibility:hidden;">
                                          <div class="form-group">
                                            <label>Nome</label>
                                            <input type="text" name="nome" class="form-control">
                                          </div>
                                          <button type="submit" class="btn btn-default">Aggiungi</button>
                                        </form>
                                      </div>
                                    </div>
                                  </div>
                                  <div class="panel panel-default">
                                    <div class="panel-heading" role="tab" id="headingTwo">
                                      <h4 class="panel-title list-group-item active">
                                        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                          Lista Modelli nel progetto <?php echo $processo->nome; ?>
                                        </a>
                                      </h4>
                                    </div>
                                    <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
                                      <div class="panel-body">
                                        <div class="table-responsive">
                                          <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                 <th>ID</th>
                                                 <th>Nome</th>
                                                 <th>Elimina</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                                $modello = new modello();
                                                $modelli = $modello->getModelli($processo->id);
                                                foreach($modelli as $key => $oprocesso)
                                                {

                                                    echo '<tr><td>'.$oprocesso->id.'</td><td><a href="index.php?page=admin&subpage=model&project_id='.$processo->id.'&id='.$oprocesso->id.'">'.$oprocesso->nome.'</a></td>';
                                                    if($user->l > 5) echo '<td><a href="index.php?page=admin&subpage=project&id='.$processo->id.'&erase_model='.$oprocesso->id.'"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></a></td>';
                                                    echo '</tr>';
                                                }
                                                
                                            ?>
                                            </tbody>
                                          </table>
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                </div>

                               </div>
                            </div>               
                        </div>


                        <?php
                    break;
                    case 'users':
                        if(!empty($_GET['method']))
                        {
                            switch($_GET['method'])
                            {
                                case 'add':
                                    $utente = new utente();
                                    $utente->crea($_POST['username'],$_POST['password'],$_POST['livello'],$_POST['nome']);
                                    if($utente->created)
                                        $cdm_mess = '<div class="alert alert-success alert-dismissible fade in" role="alert">
                                                     <button type="button" class="close" data-dismiss="alert" aria-label="Chiudi"><span aria-hidden="true">×</span></button>
                                                     <strong>Utente creato con successo!</strong></div>';
                                break;
                                case 'mod':
                                    $utente = new utente();
                                    $utente->aggiorna($_POST['mod_id'],$_POST['mod_password'],$_POST['mod_livello'],$_POST['mod_nome'],$_POST['mod_username'],$_POST['mod_zen']);
                                    if($_POST['mod_zen'] == 0)
                                        $cdm_mess = '<div class="alert alert-success alert-dismissible fade in" role="alert">
                                                     <button type="button" class="close" data-dismiss="alert" aria-label="Chiudi"><span aria-hidden="true">×</span></button>
                                                     <strong>Utente eliminato con successo!</strong></div>';
                                    else
                                        $cdm_mess = '<div class="alert alert-success alert-dismissible fade in" role="alert">
                                                     <button type="button" class="close" data-dismiss="alert" aria-label="Chiudi"><span aria-hidden="true">×</span></button>
                                                     <strong>Utente modificato con successo!</strong></div>';
                                break;
                            }
                        }

                        ?>

                        <div class="container" style="margin-top: 100px;">
                            <div class="row" style="margin-top: 15px;">
                                <div class="col-md-3">&nbsp;</div>
                                <div class="col-md-6">
                                <?php
                                if($cdm_mess) echo $cdm_mess;
                                 ?>


                                <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                                  <div class="panel panel-default">
                                    <div class="panel-heading" role="tab" id="headingOne">
                                      <h4 class="panel-title list-group-item active">
                                        <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                          Aggiungi utente
                                        </a>
                                      </h4>
                                    </div>
                                    <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
                                      <div class="panel-body">
                                        <form action="index.php?page=admin&subpage=users&method=add" method="POST">
                                          <div class="form-group">
                                            <label>Nome</label>
                                            <input type="text" name="nome" class="form-control">
                                          </div>
                                          <div class="form-group">
                                            <label>Username</label>
                                            <input type="text" name="username" class="form-control">
                                          </div>
                                          <div class="form-group">
                                            <label>Password</label>
                                            <input type="text" name="password" class="form-control">
                                          </div>
                                          <div class="form-group">
                                            <label>Livello</label>
                                            <select name="livello" class="form-control">
                                                <?php
                                                foreach($admin as $n => $nn)
                                                    echo '<option value="'.$n.'">'.$nn.'</option>';
                                                ?>
                                            </select>
                                          </div>
                                          <button type="submit" class="btn btn-default">Crea</button>
                                        </form>
                                      </div>
                                    </div>
                                  </div>
                                  <div class="panel panel-default">
                                    <div class="panel-heading" role="tab" id="headingTwo">
                                      <h4 class="panel-title list-group-item active">
                                        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                          Lista utenti
                                        </a>
                                      </h4>
                                    </div>
                                    <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
                                      <div class="panel-body">
                                        <div class="table-responsive">
                                          <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                 <th>ID</th>
                                                 <th>Nome</th>
                                                 <th>Username</th>
                                                 <th>Livello</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                                $utente = new utente();
                                                $utenti = $utente->getUsers();
                                                foreach($utenti as $key => $outente)
                                                {

                                                    echo '<tr><td>'.$outente->id.'</td><td>'.$outente->nome.'</td><td><a href="javascript:void(0);" class="cmdMod" data-id="'.$outente->id.'">'.$outente->user.'</a></td><td>'.$admin[$outente->livello].'</td></tr>';
                                                }
                                                
                                            ?>
                                            </tbody>
                                          </table>
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                </div>

                               </div>
                            </div>               
                        </div>
                        <?php
                    break;
                }
            }
            else
            {
            ?>
                <div class="container" style="margin-top: 100px;">
                    <div class="row">
                        <div class="col-md-4">&nbsp;</div>
                        <div class="col-md-4 list-group-item active">Aggiungi utente</div>
                    </div>
                    <div class="row" style="margin-top: 15px;">
                        <div class="col-md-4">&nbsp;</div>
                        <div class="col-md-4"><span class="glyphicon glyphicon-user" aria-hidden="true"></span> <a href="index.php?page=admin&subpage=users">Gestione utenti</a></div>
                    </div>
                    <div class="row" style="margin-top: 15px;">
                        <div class="col-md-4">&nbsp;</div>
                        <div class="col-md-4"><span class="glyphicon glyphicon-cog" aria-hidden="true"></span> <a href="index.php?page=admin&subpage=cdm">Gestione CDM</a></div>
                    </div>                    
                </div>
            <?php
            }
            break;
            default:
    ?>
    <div class="container" style="margin-top: 100px;width:100%;">
    <?php
        $sql = "SELECT * FROM processo WHERE zen = 1";
        $result = $db->query($sql);
        while($row = $result->fetch_assoc())
        {
    ?>
        <div class="row">
        <div class="col-md-12 list-group-item active" style="text-align: center;"><?php echo $row['nome']; ?></div>
        </div>
    <?php 
        $sql_master = "SELECT * FROM modello WHERE id_pro = ".$row['id']." AND zen = 1";
        $result = $db->query($sql_master);
        while($row = $result->fetch_assoc())
        {
            $nome_modello = $row['nome'];
            $id_modello = $row['id'];
            $sql = "SELECT * FROM lavori WHERE id_mamma = ".$row['id']." AND zen = 1 ORDER BY ordine ASC";
            $result = $db->query($sql);
            $totale_lavori = $result->num_rows;
            ?>
            <div class="row">
                <div class="col-md-1" style="padding: 10px 0px 8px 15px;margin-top:15px;border-radius: 6px; background-color:#2F71AA;color:#FFF;"><?php echo $nome_modello; ?></div>
            <?php
            while($row = $result->fetch_assoc())
            {

                $sql = "SELECT count(*) AS totale FROM lavoro WHERE id_mamma = ".$row['id']."";
                $res = $db->query($sql);
                $raw = $res->fetch_assoc();
                $tot = $raw['totale'];
                ?>
                <div class="col-md-<?php echo $tot; ?>" style="padding: 10px 0px 8px 15px;margin-top:15px;border-radius: 6px;color:#FFF; background-color:<?php echo $row['colore']; ?>;"><?php echo $row['nome']; ?></div>
                <?php
            }
            ?>
            </div>
            <?php
            $result = $db->query($sql_master);
            while($row = $result->fetch_assoc())
            {
                $parte = new parte();
                $id_modello = $row['id'];
                $parti = $parte->getParti($row['id']);
                $i = 0;
                foreach($parti as $n => $part)
                {
                    if($i == 0)
                    {
                    ?>
                    <div class="row"><div class="col-md-1" style="padding: 10px 0px 8px 15px;margin-top:5px;">&nbsp;</div>
                    <?php
                        $sql = "SELECT * FROM lavori WHERE id_mamma = ".$id_modello." AND zen = 1 ORDER BY ordine ASC";
                        $result = $db->query($sql);
                        while($row = $result->fetch_assoc())
                        {
                            $colore = $row['colore'];
                            $sql = "SELECT * FROM lavoro WHERE id_mamma = ".$row['id']." AND zen = 1 ORDER BY ordine ASC";
                            $result2 = $db->query($sql);
                            while($row2 = $result2->fetch_assoc())
                            {
                            ?>
                                <div class="col-md-1" style="padding: 10px 0px 8px 15px;margin-top:5px;color:#FFF;border-radius: 6px; background-color:<?php echo $colore; ?>"><?php echo $row2['nome']; ?></div>
                            <?php
                            }
                        }
                        ?>
                            </div>
                            <?php
                    $i = 1;
                    }
                    ?>
                    <div class="row"><div class="col-md-1" style="padding: 10px 0px 8px 15px;margin-top:5px;"><?php echo $part->nome; ?></div>
                    <?php 
                    $sql = "SELECT * FROM lavori WHERE id_mamma = ".$id_modello." AND zen = 1 ORDER BY ordine ASC";
                    $result = $db->query($sql);
                    while($row = $result->fetch_assoc())
                    {
                        $colore = $row['colore'];
                        $sql = "SELECT * FROM lavoro WHERE id_mamma = ".$row['id']." AND zen = 1 ORDER BY ordine ASC";
                        $result2 = $db->query($sql);
                        while($row2 = $result2->fetch_assoc())
                        {
                            ?>
                            <div class="col-md-1">
                            <?php
                            $sql = "SELECT * FROM articolo WHERE id_modello = ".$id_modello." AND id_parte = ".$part->id." AND zen = 1 AND finito = 0 AND pos_madre = ".$row['ordine']." AND posizione = ".$row2['ordine']."";
                            $result3 = $db->query($sql);
                            while($row3 = $result3->fetch_assoc())
                            {
                                ?>
                                <a href="index.php?sposta=<?php echo $row3['id']; ?>"><button type="button" class="btn btn-sm spostami <?php echo $row3['colore']; ?>" data-id="<?php echo $row3['id']; ?>" style="margin-top:5px;"><?php echo $row3['nome']; ?></button></a>
                                <?php
                            }
                            if($user->l > 4 && isset($_SESSION['aggiungere'])) { ?> <a class="addArti" href="javascript:void(0);" data-posizione="<?php echo $row2['ordine']; ?>" data-id-modello="<?php echo $id_modello; ?>" data-id-parte="<?php echo $part->id; ?>" data-pos-madre="<?php echo $row['ordine']; ?>"><button type="button" class="btn btn-sm spostami" style="margin-top:5px;"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></button> <?php } ?></a>
                            </div>
                            <?php
                        }
                    }
                    ?>
                    </div>
                    <?php
                
                }
            }

        }
    ?>        

    </div>


    <?php 
        }
    } 
}
    ?>

<script src="js.js"></script>
</body>

</html>
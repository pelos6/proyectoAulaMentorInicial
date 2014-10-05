<?php 
// separando las funciones de la pantalla
require ("dat_bic.php");
// que se gestiona en esta página
define("que","bic");
var_que(que);
?>
<html>
    <LINK HREF="tubici.css" REL="stylesheet" TYPE="text/css">
    <head>
        <title>Proyecto TuBici - Gestión de <?php echo(ucfirst(strtolower($tit_plu)))?></title>
    </head>
    <body>
        <table id="titulo">
            <tbody><tr>
                    <th>
                        GESTIÓN DE <?php echo($tit_plu)?>
                    </th>
                </tr>
            </tbody>
        </table><p>
        </p><center><p>
            <table width="600" border="0">
                <tbody>
                    <tr>
                        <td valign="top" align="CENTER" colspan="2">
                            <form action=<?php  $_SERVER['PHP_SELF']?>?operacion=bus_bic method="POST" name="form1">
                                <font size="-1">Buscar por el campo
                                    <select name="cam_bus">
                                        <option value="cod_bic"> Código <?php echo(ucfirst(strtolower($tit_sin)))?> </option>
                                        <option value="sit_bic"> Situación <?php echo(ucfirst(strtolower($tit_sin)))?> </option>
                                    </select> </font><p><font size="-1"><input type="TEXT" size="20" value="" name="cod_bus">
                                        <input type="SUBMIT" value="Buscar" name="boton_buscar">
                                    </font>
                                </p></form></td>
                        <td align="center">
                            <form action=<?php $_SERVER['PHP_SELF']?>?operacion=nue_bic method="POST" name="form2">
                                <input type="SUBMIT" value="Nueva <?php echo(ucfirst(strtolower($tit_sin)))?>" name="alta">
                            </form>
                            <form action=<?php $_SERVER['PHP_SELF']?>?operacion=lis_bic method="POST" name="form3">
                                <input type="SUBMIT" value="Listado completo" name="lis">
                            </form>
                            <form action=<?php $_SERVER['PHP_SELF']?>?operacion=lis_pdf method="POST" name="form3">
                                <input type="SUBMIT" value="Listado PDF" name="lis_pdf">
                            </form>
                            <a href="TuBici.pdf">Ver PDF</a>
                        </td>
                    </tr></tbody></table>
            <?php 
            // chequeando los valores de entrada para la busqueda
            if (!isset($_REQUEST["cam_bus"]))$cam_bus="";
            else {$cam_bus=$_REQUEST["cam_bus"];}
            if (!isset($_REQUEST["cod_bus"]))$cod_bus="";
            else {$cod_bus=$_REQUEST["cod_bus"];}

            //Si no llega operación la operación por defecto es listar la tabla a gestionar
            if (!isset($_REQUEST["operacion"]))$ope="lis_".que;
            else {$ope=$_REQUEST["operacion"];}

            // los datos para el alta y la edición
            if (!isset($_REQUEST["cod_pos_bic"]))$cod_pos_bic="";
            else {$cod_pos_bic=$_REQUEST["cod_pos_bic"];}
            if (!isset($_REQUEST["cod_par_bic"]))$cod_par_bic="";
            else {$cod_par_bic=$_REQUEST["cod_par_bic"];}
            if (!isset($_REQUEST["gra_mod_bic"]))$gra_mod_bic="";
            else {$gra_mod_bic=$_REQUEST["gra_mod_bic"];}
            if (!isset($_REQUEST["l_act"]))$l_act="";
            else {$l_act=$_REQUEST["l_act"];}
            if (!isset($_REQUEST["id"]))$id="";//la primary key del registro a tratar
            else {$id=$_REQUEST["id"];}
          //  Para debugear. Salen las variables pasadas desde la pantalla
//                        echo "<pre>";
//                        print_r($_REQUEST);
//                        echo "</pre>";
            // para comprobaciones y debugear. hasta encontrar una herramiento más eficaz
            //            echo("campos de busqueda:".$cam_bus."-codigo de busqueda:".$cod_bus."-operacion:".$ope."-<br>
            //                            nom_usu:".$nom_usu."-fec_ini:".$fec_ini."-avtivo:".$l_act."-id:".$id."<br>");
            $id_con = con();
            if ($cam_bus=="cod_bic") {$tex_sen = "select cod_".que. " ,(select dir_pos from pos p where p.cod_pos = b.cod_pos),cod_par,l_act from ".que. " b where ".$cam_bus." like ".'"'."%".$cod_bus."%".'"';}
            else {$tex_sen = "select cod_".que. ",
            (select dir_pos from pos p where p.cod_pos = b.cod_pos),
            cod_par,l_act from ".que. " b where
            b.cod_pos in (select cod_pos from pos c where c.dir_pos like ".'"'."%".$cod_bus."%".'")';}
            //$tex_sen_all ="select cod_".que. " ,(select dir_pos from pos p where p.cod_pos = b.cod_pos),cod_par,l_act from ".que." b ";
            $tex_sen_all="select cod_".que. " ,IFNULL((
                            SELECT CONCAT('Aparcada en ',dir_pos)
                            FROM pos p
                            WHERE p.cod_pos = b.cod_pos),(
                            SELECT CONCAT('Alquilada a ',nom_usu)
                            FROM usu
                            WHERE usu.cod_bic = b.cod_bic)), IFNULL((CONCAT('Plaza: ',cod_par)),(
                            SELECT CONCAT('Usuario: ',cod_usu)FROM usu
                            WHERE usu.cod_bic = b.cod_bic)),l_act
                            FROM bic b";
            if (strlen($gra_mod_bic)>0) $ope="nue_edi_bic";
            switch ($ope) {
                case "bus_bic": //si llega patron a buscar se busca. Si no como listar
                    if (strlen($cod_bus)>0) {
                        lis_bic($id_con,$tex_sen,$ope);}
                    else {
                        echo("La opción de busqueda necesita un patrón de busqueda");
                        lis_usu($id_con,$tex_sen_all,$ope);}
                    break;
                case "lis_bic"://se conecta, lista y cierra
                    lis_bic($id_con,$tex_sen_all,$ope);
                    break;
                case "nue_bic"://pantalla para nuevo registro
                    nue_bic($cod_pos_bic);
                    break;
                case "nue_alt_bic"://control de datos y alta articulo
                    if (strlen($cod_pos_bic)==0) {echo("No se puede realizar la operación. Se debe indicar el poste.");}
                    elseif (strlen($cod_par_bic)==0) {echo("No se puede realizar la operación. Se debe indicar la plaza del poste. ");}
                    else {
                        $tex_alt_usu = "insert into ".que." values(0,'".$cod_pos_bic."','".$cod_par_bic."','S')";
                        eje_sen($id_con,$tex_alt_usu);
                        lis_bic($id_con,$tex_sen_all);}
                    break;
                case "del_bic"://borrar un articulo
                    if (strlen($id)==0) {echo("No se puede borrar un ".strtolower($tit_sin)." si no sabemos el código");}
                    else {
                        $tex_del_usu="delete from ".que." where cod_".que." ='".$id."'";
                        eje_sen($id_con,$tex_del_usu);
                        lis_bic($id_con,$tex_sen_all);}
                    break;
                case "edi_bic"://pantalla para editar un registro
                    if (strlen($id)==0) {echo("No se puede editar un ".strtolower($tit_sin)." si no sabemos el código");}
                    edi_bic($id_con,$id,$cod_pos_bic);
                    break;
                case "nue_edi_bic"://control de datos y edición de un registro
                    if (strlen($cod_pos_bic)==0) {echo("No se puede realizar la operación. Se debe indicar el poste.");}
                    elseif (strlen($cod_par_bic)==0) {echo("No se puede realizar la operación. Se debe indicar la plaza del poste. ");}
                    else {
                        $tex_edi_bic = "update ".que." set cod_pos= '".$cod_pos_bic."' , cod_par = '".$cod_par_bic."', l_act = '".$l_act."' where cod_".que." = ".$id;
                        eje_sen($id_con,$tex_edi_bic);
                        lis_bic($id_con,$tex_sen_all);}
                    break;
                case "lis_pdf":
                    lis_pdf($id_con,$tex_sen_all,que);
                    lis_bic($id_con,$tex_sen_all);
                    break;
                default:
                    echo("Error Grave. Llega operacion no controlada $ope");
            }
            clo($id_con);
            echo " <p>
            <table><tbody><tr>
                        <td><font color=\"#0000c0\" size=\"-1\">El n° total de ".strtolower($tit_plu)." es: <b>".num_que(que)."</b></font><p></p></td>
                    </tr>
            </table>";
            ?>
            <br>
            <a href="index.php"> Página inicial</a>
        </center>
    </body>
</html>



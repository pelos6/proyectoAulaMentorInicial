<?php 
// separando las funciones de la pantalla
require ("dat_bic.php");
// que se gestiona en esta página
define("que","pos");
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
                            <form action=<?php $_SERVER['PHP_SELF']?>?operacion=bus_pos method="POST" name="form1">
                                <font size="-1">Buscar por el campo
                                    <select name="cam_bus">
                                        <option value="cod_pos"> Código <?php echo(ucfirst(strtolower($tit_sin)))?> </option>
                                        <option value="dir_pos"> Dirección <?php echo(ucfirst(strtolower($tit_sin)))?> </option>
                                    </select> </font><p><font size="-1"><input type="TEXT" size="20" value="" name="cod_bus">
                                        <input type="SUBMIT" value="¡Buscar!" name="boton_buscar">
                                    </font>

                                </p></form></td>
                        <td align="center">
                            <form action=<?php $_SERVER['PHP_SELF']?>?operacion=nue_pos method="POST" name="form2">
                                <input type="SUBMIT" value="Nuevo <?php echo(ucfirst(strtolower($tit_sin)))?>" name="alta">
                            </form>
                            <form action=<?php $_SERVER['PHP_SELF']?>?operacion=lis_pos method="POST" name="form3">
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
            if (!isset($_REQUEST["dir_pos"]))$dir_pos="";
            else {$dir_pos=$_REQUEST["dir_pos"];}
            if (!isset($_REQUEST["num_par"]))$num_par="";
            else {$num_par=$_REQUEST["num_par"];}
            if (!isset($_REQUEST["l_act"]))$l_act="";
            else {$l_act=$_REQUEST["l_act"];}
            if (!isset($_REQUEST["id"]))$id="";//la primary key del registro a tratar
            else {$id=$_REQUEST["id"];}

            //   para comprobaciones y debugear. hasta encontrar una herramiento más eficaz
            //                        echo("campos de busqueda:".$cam_bus."-codigo de busqueda:".$cod_bus."-operacion:".$ope."-<br>
            //                                        dir_pos:".$dir_pos."-num_par:".$num_par."-activo:".$l_act."-id:".$id."<br>");
            $id_con = con();
            $tex_sen = "select cod_".que." ,dir_".que." ,num_par,num_par - (select count(*) from bic where bic.cod_pos = pos.cod_pos)num_lib,  l_act from ".que. " where ".$cam_bus." like ".'"'."%".$cod_bus."%".'"';
            $tex_sen_all ="select cod_".que. " ,dir_".que." ,num_par, num_par - (select count(*) from bic where bic.cod_pos = pos.cod_pos)num_lib ,l_act from ".que;
            switch ($ope) {
                case "bus_pos": //si llega patron a buscar se busca. Si no como listar
                    if (strlen($cod_bus)>0) {
                        lis_pos($id_con,$tex_sen,$ope);}
                    //clo(lis_usu(con(),sen_usu($cam_bus,$cod_bus),$ope));}
                    else {
                        echo("La opción de busqueda necesita un patrón de busqueda");
                        lis_pos($id_con,$tex_sen_all,$ope);}
                    break;
                case "lis_pos"://se conecta, lista y cierra
                    lis_pos($id_con,$tex_sen_all,$ope);
                    break;
                case "nue_pos"://pantalla para nuevo registro
                    nue_pos();
                    break;
                case "nue_alt_pos"://control de datos y alta
                    if (strlen($dir_pos)==0) {echo("No se puede realizar la operación. El campo Dirección del poste es obligatorio");}
                    elseif (strlen($num_par)==0) {echo("No se puede realizar la operación. El campo Número de plazas es obligatorio");}
                    elseif ($num_par>10) {echo("No se puede realizar la operación. Número de plazas superior a 10");}
                    else {
                        $tex_alt_pos = "insert into ".que." values(0,'".$dir_pos."','".$num_par."','S')";
                        eje_sen($id_con,$tex_alt_pos);
                        lis_pos($id_con,$tex_sen_all);}
                    break;
                case "del_pos"://borrar
                    if (strlen($id)==0) {echo("No se puede borrar un ".strtolower($tit_sin)." si no sabemos el código");}
                    else {
                        $tex_del_pos="delete from ".que." where cod_".que." ='".$id."'";
                        eje_sen($id_con,$tex_del_pos);
                        lis_pos($id_con,$tex_sen_all);}
                    break;
                case "edi_pos"://pantalla para editar un registro
                    if (strlen($id)==0) {echo("No se puede editar un ".strtolower($tit_sin)." si no sabemos el código");}
                    edi_pos($id_con,$id);
                    break;
                case "nue_edi_pos"://control de datos y edición de un registro
                    if (strlen($dir_pos)==0) {echo("No se puede realizar la operación. El campo Dirección del poste es obligatorio");}
                    elseif (strlen($num_par)==0) {echo("No se puede realizar la operación. El campo Número de plazas es obligatorio");}
                    elseif ($num_par>10) {echo("No se puede realizar la operación. Número de plazas superior a 10");}
                    else {
                        $tex_edi_usu = "update ".que." set dir_".que. "= '".$dir_pos."' , num_par = '".$num_par."', l_act = '".$l_act."' where cod_".que." = ".$id;
                        eje_sen($id_con,$tex_edi_usu);
                        lis_pos($id_con,$tex_sen_all);}
                    break;
                case "lis_pdf":
                //clock();
                    lis_pdf($id_con,$tex_sen_all,que);
                    lis_pos($id_con,$tex_sen_all);
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

          <!--  <input type="button" value="Volver a la página anterior" onClick="history.back()"> -->
            <a href="index.php"> Página inicial</a>
        </center>
    </body>
</html>


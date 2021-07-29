<div class="modal-content">
    <div class=" card-panel">
        <div class="row">
            <form class="col l12">
                <div class="row">
                    <div class="input-field col l10">
                        <select id="ties_es" name="ties_es">
                            <option value="" disabled selected> </option>
                            <option value="1">Sala cuana </option>
                            <option value="2">Jardin infantil</option>
                            <option value="3">Basica </option>
                            <option value="4">Media </option>
                        </select>
                        <label for="ties_es">Tipo establecimiento</label>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col l10">
                        <i class="mdi-social-person prefix"></i>
                        <input id="nombre_es" type="text" onkeypress="return soloLetras(event)" class="atributosText" name="nombre_es">
                        <label for="nombre_es">Nombre</label>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col l10">
                        <i class="mdi-maps-directions prefix"></i>
                        <input id="dire_es" type="text" onkeypress="return soloLetras(event)" class="atributosText" name="dire_es">
                        <label for="dire_es">Direccion</label>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col l10">
                        <i class="mdi-content-mail prefix"></i>
                        <input id="mail_es" type="text" onkeypress="return soloLetras(event)" class="atributosText" name="mail_es">
                        <label for="mail_es">Mail</label>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col l10">
                        <i class="mdi-communication-phone prefix"></i>
                        <input id="tel_es" type="text" onkeypress="return soloLetras(event)" class="atributosText" name="tel_es">
                        <label for="tel_es">Telefono</label>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col l10">
                        <i class="mdi-action-today prefix"></i>
                        <input type="text"   id="data_es" name="date_es">
                        <label for="data_es">fecha de creacion del establecimiento</label>
                    </div>
                </div>
                <div class="row">
                    <div class="browser-default col l10">
                        <select id="comuna" name="comuna">
                            <option value="" disabled selected> </option>
                            <option value="1">Nueva Imperial </option>
                            <option value="2">Carahue</option>
                            <option value="3">Tolten </option>
                            <option value="4">Teodoro schmidt </option>
                            <option value="5">Pto saavedra</option>
                        </select>
                        <label for="comuna">  comunas</label>
                    </div>
                </div>

                <div class="row">
                    <div class="col l4">
                        <h4 class="header">Seleccione </h4>
                        <p class="left"> selecione uno o mas elementos que tenga el establecimiento</p>
                    </div>
                    <div class="col l4">
                        <p>
                            <input type="checkbox" id="est_e" name="est_e" />
                            <label for="est_e">Estacionamiento</label>
                        <p>
                            <input type="checkbox" id="gim_es" name="gim_es" />
                            <label for="gim_es">Gimnasio</label>
                        </p>
                        <p>
                            <input type="checkbox" id="inter_es"  name="inter_es"/>
                            <label for="inter_es">Internado</label>
                        </p>
                        <p>
                            <input type="checkbox" id="pa_te_es" name="pa_te_es" />
                            <label for="pa_te_es">Patio techado</label>
                        </p>
                        <p>
                            <input type="checkbox" id="bil_es" name="bil_es" />
                            <label for="bil_es">Bibloteca</label>
                        </p>
                        <p>
                            <input type="checkbox" id="la_com_es" name="la_com_es" />
                            <label for="la_com_es">Laboratorio de computacion</label>
                        </p>
                        <p>
                            <input type="checkbox" id="la_qui_es" name="la_qui_es" />
                            <label for="la_qui_es">Laboratorio de quimica</label>
                        </p>

                    </div>
                    <div class="col l4">
                        <p>
                            <input type="checkbox" id="cun_es"  name="cun_es"/>
                            <label for="cun_es">Sala de cuna</label>
                        <p>
                            <input type="checkbox" id="jar_es" name="jar_es" />
                            <label for="jar_es">Jardir infantil</label>
                        </p>
                        <p>
                            <input type="checkbox" id="bas_es" name="bas_es" />
                            <label for="bas_es">Basica</label>
                        </p>
                        <p>
                            <input type="checkbox" id="med_es" name="med_es" />
                            <label for="med_es">Media</label>
                        </p>
                        <p>
                            <input type="checkbox" id="tp_es" name="tp_es" />
                            <label for="tp_es">Tecnico profesional</label>
                        </p>
                        <p>
                            <input type="checkbox" id="hum_es" name="hum_es" />
                            <label for="hum_es">Humanista </label>
                        </p>
                        <p>
                            <input type="checkbox" id="acceso_universal" name="acceso_universal" />
                            <label for="acceso_universal">Accesibilidad Universal</label>
                        </p>
                    </div>



                </div>
                <div class="row">
                    <div class="input-field col s12">
                        <a href="index.html" class="btn waves-effect waves-light  col s12"> Crear Establecimiento</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!--
<div class="modal-footer">
    <a href="#" class="waves-effect waves-red btn-flat modal-action modal-close">Disagree</a>
    <a href="#" class="waves-effect waves-green btn-flat modal-action modal-close">Agree</a>
</div>
-->
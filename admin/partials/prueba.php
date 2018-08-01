 <!-- Modal Structure -->
  <div id="NewStudentModal" class="modal">
    <div class="modal-content">
      <h4>Agregar Estudiante</h4>
      <form method="post">
            <div class="row">
                <div class="input-field col s6">
                <input type="text" id="firstname-input" class="firstname">
                <label for="firstname-input">Nombre</label>
                </div>
                <div class="input-field col s6">
                <input type="text" id="lastname-input" class="lastname">
                <label for="lastname-input">Apellido</label>
                </div>
            </div>
            <div class="row">
                <div class="input-field col s12">
                <input type="text" id="personalid-input" class="personalid">
                <label for="personalid-input">Cedula</label>
                </div>
            </div>
            <div class="row">
                <div class="tr"></div>
            </div>
            <div class="row">
                <div class="input-field col s4">
                <input type="text" id="autocomplete-input" class="autocomplete">
                <label for="autocomplete-input">Autocomplete</label>
                </div>
                <div class="input-field col s4">
                <input type="text" id="autocomplete-input" class="autocomplete">
                <label for="autocomplete-input">Autocomplete</label>
                </div>
                <div class="input-field col s4">
                <input type="text" id="autocomplete-input" class="autocomplete">
                <label for="autocomplete-input">Autocomplete</label>
                </div>
            </div>
            <div class="row">
                <div class="col s12">
                    <button id="SaveNewStudent" class="btn waves-effect waves-light" type="button">Guardar <i class="material-icons">add</i></button>
                </div>
            </div>
      </form>
    </div>
    <div class="modal-footer">
      <a href="#!" class="modal-close waves-effect waves-green btn-flat">Agree</a>
    </div>
  </div>

<div class="had-container">
        <!-- Page Content goes here -->
    <div class="row">
        <div class="col s12">
            <h5><?php echo esc_html(get_admin_page_title); ?></h5>
        </div>
    </div>
    
    <div class="row">
        <div class="col s4">
            <a class="btnNewRow btn btn-floating pulse"><i class="material-icons">add</i></a>
            <span>Nuevo Registro</span>
        </div>
    </div>
</div>
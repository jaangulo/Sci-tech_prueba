<template>
  <main class="main">
    <!-- Breadcrumb -->
    <ol class="breadcrumb">
      <li class="breadcrumb-item">
        <a href="/">Escritorio</a>
      </li>
    </ol>
    <div class="container-fluid">
      <!-- Ejemplo de tabla Listado -->
      <div class="card">
        <div class="card-header">
          <i class="fa fa-align-justify"></i> Vehiculos
          <button
            type="button"
            @click="abrirModal('vehiculos', 'registrar')"
            class="btn btn-secondary"
          >
            <i class="icon-plus"></i>&nbsp;Nuevo
          </button>
        </div>
        <div class="card-body">
          <div class="form-group row">
            <div class="col-md-6">
              <div class="input-group">
                <select class="form-control col-md-3" v-model="criterio">
                  <option value="marca">Marca</option>
                  <option value="tipovehiculo">Tipo de vehiculo</option>
                </select>
                <input
                  type="text"
                  v-model="buscar"
                  @keyup.enter="
                                        listarVehiculo(1, buscar, criterio)
                                    "
                  class="form-control"
                  placeholder="Texto a buscar"
                />
                <button
                  type="submit"
                  @click="listarVehiculo(1, buscar, criterio)"
                  class="btn btn-primary"
                >
                  <i class="fa fa-search"></i> Buscar
                </button>
              </div>
            </div>
          </div>
          <table class="table table-bordered table-striped table-sm">
            <thead>
              <tr>
                <th>opciones</th>
                <th>placa</th>
                <th>marca</th>
                <th># puertas</th>
                <th>tipo vehiculo</th>
                <th>modelo</th>
                <th>Estado</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="vehiculos in arrayVehiculo" :key="vehiculos.id">
                <td>
                  <button
                    type="button"
                    @click="
                                            abrirModal(
                                                'vehiculos',
                                                'actualizar',
                                                vehiculos
                                            )
                                        "
                    class="btn btn-warning btn-sm"
                  >
                    <i class="icon-pencil"></i>
                  </button>
                  &nbsp;
                  <template v-if="vehiculos.condicion">
                    <button
                      type="button"
                      class="btn btn-danger btn-sm"
                      @click="
                                                desactivarVehiculo(vehiculos.id)
                                            "
                    >
                      <i class="icon-trash"></i>
                    </button>
                  </template>
                  <template v-else>
                    <button
                      type="button"
                      class="btn btn-info btn-sm"
                      @click="
                                                activarVehiculo(vehiculos.id)
                                            "
                    >
                      <i class="icon-check"></i>
                    </button>
                  </template>
                </td>
                <td v-text="vehiculos.placa"></td>
                <td v-text="vehiculos.marca"></td>
                <td v-text="vehiculos.npuertas"></td>
                <td v-text="vehiculos.tipovehiculo"></td>
                <td v-text="vehiculos.modelo"></td>
                <td>
                  <div v-if="vehiculos.condicion">
                    <span class="badge badge-success">Activo</span>
                  </div>
                  <div v-else>
                    <span class="badge badge-danger">Desactivado</span>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
          <nav>
            <ul class="pagination">
              <li class="page-item" v-if="pagination.current_page > 1">
                <a
                  class="page-link"
                  href="#"
                  @click.prevent="
                                        cambiarPagina(
                                            pagination.current_page - 1,
                                            buscar,
                                            criterio
                                        )
                                    "
                >Ant</a>
              </li>
              <li
                class="page-item"
                v-for="page in pagesNumber"
                :key="page"
                :class="[page == isActived ? 'active' : '']"
              >
                <a
                  class="page-link"
                  href="#"
                  @click.prevent="
                                        cambiarPagina(page, buscar, criterio)
                                    "
                  v-text="page"
                ></a>
              </li>
              <li
                class="page-item"
                v-if="
                                    pagination.current_page <
                                        pagination.last_page
                                "
              >
                <a
                  class="page-link"
                  href="#"
                  @click.prevent="
                                        cambiarPagina(
                                            pagination.current_page + 1,
                                            buscar,
                                            criterio
                                        )
                                    "
                >Sig</a>
              </li>
            </ul>
          </nav>
        </div>
      </div>
      <!-- Fin ejemplo de tabla Listado -->
    </div>
    <!--Inicio del modal agregar/actualizar-->
    <div
      class="modal fade"
      tabindex="-1"
      :class="{ mostrar: modal }"
      role="dialog"
      aria-labelledby="myModalLabel"
      style="display: none;"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-primary modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title" v-text="tituloModal"></h4>
            <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body">
            <form action method="post" enctype="multipart/form-data" class="form-horizontal">
              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="text-input">Placa</label>
                <div class="col-md-9">
                  <input type="text" v-model="placa" class="form-control" placeholder="Placa" />
                </div>
              </div>
              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="text-input">Marca</label>
                <div class="col-md-9">
                  <input type="text" v-model="marca" class="form-control" placeholder="Marca" />
                </div>
              </div>
              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="text-input">Modelo</label>
                <div class="col-md-9">
                  <input type="text" v-model="modelo" class="form-control" placeholder="Modelo" />
                </div>
              </div>
              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="text-input">Número de puertas</label>
                <div class="col-md-9">
                  <input
                    type="number"
                    v-model="npuertas"
                    class="form-control"
                    placeholder="Número de puertas"
                  />
                </div>
              </div>
              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="email-input">Tipo de vehículo</label>
                <div class="col-md-9">
                  <input
                    type="email"
                    v-model="tipovehiculo"
                    class="form-control"
                    placeholder="Tipo de vehículo"
                  />
                </div>
              </div>

              <div v-show="errorVehiculo" class="form-group row div-error">
                <div class="text-center text-error">
                  <div v-for="error in errorMostrarMsjVehiculo" :key="error" v-text="error"></div>
                </div>
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
            <button
              type="button"
              v-if="tipoAccion == 1"
              class="btn btn-primary"
              @click="registrarVehiculo()"
            >Guardar</button>
            <button
              type="button"
              v-if="tipoAccion == 2"
              class="btn btn-primary"
              @click="actualizarVehiculo()"
            >Actualizar</button>
          </div>
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>
    <!--Fin del modal-->
  </main>
</template>

<script>
export default {
  data() {
    return {
      vehiculos_id: 0,
      marca: "",
      placa: "",
      modelo: "",
      npuertas: "",
      tipovehiculo: "",
      descripcion: "",
      arrayVehiculo: [],
      modal: 0,
      tituloModal: "",
      tipoAccion: 0,
      errorVehiculo: 0,
      errorMostrarMsjVehiculo: [],
      pagination: {
        total: 0,
        current_page: 0,
        per_page: 0,
        last_page: 0,
        from: 0,
        to: 0
      },
      offset: 3,
      criterio: "marca",
      buscar: ""
    };
  },
  computed: {
    isActived: function() {
      return this.pagination.current_page;
    },
    //Calcula los elementos de la paginación
    pagesNumber: function() {
      if (!this.pagination.to) {
        return [];
      }

      var from = this.pagination.current_page - this.offset;
      if (from < 1) {
        from = 1;
      }

      var to = from + this.offset * 2;
      if (to >= this.pagination.last_page) {
        to = this.pagination.last_page;
      }

      var pagesArray = [];
      while (from <= to) {
        pagesArray.push(from);
        from++;
      }
      return pagesArray;
    }
  },
  methods: {
    listarVehiculo(page, buscar, criterio) {
      let me = this;
      var url =
        "/vehiculos?page=" +
        page +
        "&buscar=" +
        buscar +
        "&criterio=" +
        criterio;
      axios
        .get(url)
        .then(function(response) {
          var respuesta = response.data;
          me.arrayVehiculo = respuesta.vehiculos.data;
          me.pagination = respuesta.pagination;
        })
        .catch(function(error) {
          console.log(error);
        });
    },
    cambiarPagina(page, buscar, criterio) {
      let me = this;
      //Actualiza la página actual
      me.pagination.current_page = page;
      //Envia la petición para visualizar la data de esa página
      me.listarVehiculo(page, buscar, criterio);
    },
    registrarVehiculo() {
      if (this.validarVehiculo()) {
        return;
      }

      let me = this;

      axios
        .post("/vehiculos/registrar", {
          placa: this.placa,
          marca: this.marca,
          modelo: this.modelo,
          npuertas: this.npuertas,
          tipovehiculo: this.tipovehiculo
        })
        .then(function(response) {
          me.cerrarModal();
          me.listarVehiculo(1, "", "marca");
        })
        .catch(function(error) {
          console.log(error);
        });
    },
    actualizarVehiculo() {
      if (this.validarVehiculo()) {
        return;
      }

      let me = this;

      axios
        .put("/vehiculos/actualizar", {
          placa: this.placa,
          marca: this.marca,
          modelo: this.modelo,
          npuertas: this.npuertas,
          tipovehiculo: this.tipovehiculo,
          id: this.vehiculos_id
        })
        .then(function(response) {
          me.cerrarModal();
          me.listarVehiculo(1, "", "placa");
        })
        .catch(function(error) {
          console.log(error);
        });
    },
    desactivarVehiculo(id) {
      swal({
        title: "Esta seguro de desactivar el Vehiculo?",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Aceptar!",
        cancelButtonText: "Cancelar",
        confirmButtonClass: "btn btn-success",
        cancelButtonClass: "btn btn-danger",
        buttonsStyling: false,
        reverseButtons: true
      }).then(result => {
        if (result.value) {
          let me = this;

          axios
            .put("/vehiculos/desactivar", {
              id: id
            })
            .then(function(response) {
              me.listarVehiculo(1, "", "nombre");
              swal(
                "Desactivado!",
                "El registro ha sido desactivado con éxito.",
                "success"
              );
            })
            .catch(function(error) {
              console.log(error);
            });
        } else if (
          // Read more about handling dismissals
          result.dismiss === swal.DismissReason.cancel
        ) {
        }
      });
    },
    activarVehiculo(id) {
      swal({
        title: "Esta seguro de activar el vehiculo?",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Aceptar!",
        cancelButtonText: "Cancelar",
        confirmButtonClass: "btn btn-success",
        cancelButtonClass: "btn btn-danger",
        buttonsStyling: false,
        reverseButtons: true
      }).then(result => {
        if (result.value) {
          let me = this;

          axios
            .put("/vehiculos/activar", {
              id: id
            })
            .then(function(response) {
              me.listarVehiculo(1, "", "nombre");
              swal(
                "Activado!",
                "El registro ha sido activado con éxito.",
                "success"
              );
            })
            .catch(function(error) {
              console.log(error);
            });
        } else if (
          // Read more about handling dismissals
          result.dismiss === swal.DismissReason.cancel
        ) {
        }
      });
    },
    validarVehiculo() {
      this.errorVehiculo = 0;
      this.errorMostrarMsjVehiculo = [];

      if (!this.placa)
        this.errorMostrarMsjVehiculo.push(
          "El campo de la placa no puede estar vacío."
        );

      if (this.errorMostrarMsjVehiculo.length) this.errorVehiculo = 1;

      return this.errorVehiculo;
    },
    cerrarModal() {
      this.modal = 0;
      this.tituloModal = "";
      this.nombre = "";
      this.descripcion = "";
    },
    abrirModal(modelo, accion, data = []) {
      switch (modelo) {
        case "vehiculos": {
          switch (accion) {
            case "registrar": {
              this.modal = 1;
              this.tituloModal = "Registrar Vehiculo";
              this.nombre = "";
              this.descripcion = "";
              this.tipoAccion = 1;
              break;
            }
            case "actualizar": {
              console.log(data);
              this.modal = 1;
              this.tituloModal = "Actualizar Vehiculo";
              this.tipoAccion = 2;
              this.vehiculos_id = data["id"];
              this.placa = data["placa"];
              this.marca = data["marca"];
              this.modelo = data["modelo"];
              this.npuertas = data["npuertas"];
              this.tipovehiculo = data["tipovehiculo"];
             
              break;
            }
          }
        }
      }
    }
  },
  mounted() {
    this.listarVehiculo(1, this.buscar, this.criterio);
  }
};
</script>
<style>
.modal-content {
  width: 100% !important;
  position: absolute !important;
}
.mostrar {
  display: list-item !important;
  opacity: 1 !important;
  position: absolute !important;
  background-color: #3c29297a !important;
}
.div-error {
  display: flex;
  justify-content: center;
}
.text-error {
  color: red !important;
  font-weight: bold;
}
</style>

<?php
    if($_SESSION['permisos_acceso']=='Super Admin'){?>

        <section class="content-header"> 
            <h1>
                <i class="fa fa-home icon-title">Inicio</i>
            </h1>
            <ol class="breadcrumb">
                <li><a href="?module=start"><i class="fa fa-home"></i></a></li>
            </ol>
        </section>
        <section class="content">
                <div class="row">
                    <div class="col-lg-12 col-xs-12">
                        <div class="alert alert-info alert-dismissable">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            <p style="font-size:15px">
                                <i class="icon fa fa-user"></i>Bienvenido/a <strong><?php echo $_SESSION['name_user'] ?></strong>
                                a la aplicación: <strong>SysWeb</strong>
                            </p>
                        </div>
                    </div>
                </div>
            
            <h2>Formulario de movimiento</h2>
            <div class="row">
                    <!--Bloque de compras-->
                    <div class="col-lg-4 col-xs-6">
                        <div style="background-color:#27E0F5; color:#fff" class="small-box">
                            <div class="inner">
                                <p><strong>Compras</strong>
                                    <ul>
                                        <li>Registrar</li>
                                        <li>la compra</li>
                                        <li>de productos</li>
                                    </ul>
                                </p>
                            </div>
                            <div class="icon">
                                <i class="glyphicon glyphicon-piggy-bank"></i>
                            </div>
                                <a href="?module=compras" class="small-box-footer" title="Registrar Compras" data-toggle="tooltip">
                                    <i class="fa fa-plus"> </i></a>
                        </div>
                    </div>
                    <!--Fin bloque de compras-->

                    <!--Bloque de ventas-->
                    <div class="col-lg-4 col-xs-6">
                        <div style="background-color:#12B371; color:#fff" class="small-box">
                            <div class="inner">
                                <p><strong>Ventas</strong>
                                    <ul>
                                        <li>Registrar</li>
                                        <li>Ventas</li>
                                        <li>de productos</li>
                                    </ul>
                                </p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-cart-plus"></i>
                            </div>
                                <a href="?module=ventas" class="small-box-footer" title="Registrar Ventas" data-toggle="tooltip">
                                    <i class="fa fa-plus"> </i></a>
                        </div>
                    </div>
                    <!--Fin bloque de compras-->

                    <!--Bloque de stock-->
                    <div class="col-lg-4 col-xs-6">
                        <div style="background-color:#f39c12; color:#fff" class="small-box">
                            <div class="inner">
                                <p><strong>Stock de productos</strong>
                                    <ul>
                                        <li>Visualizar</li>
                                        <li>Stock</li>
                                        <li>de productos</li>
                                    </ul>
                                </p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-area-chart"></i>
                            </div>
                                <a href="?module=stock" class="small-box-footer" title="Ver Stock de productos" data-toggle="tooltip">
                                    <i class="fa fa-plus"> </i></a>
                        </div>
                    </div>
                    <!--Fin bloque de stock-->

                <div class="col-xl-4 col-lg-5">
                    <div class="card no-shutdown mb-4">
                        <div class="card-header py-3 d-flex-row align-item-center justify-content-between"></div>
                    </div>
                </div>
        </div>

    </section>
<?php } elseif($_SESSION['permisos_acceso']=='Compras'){ ?>

    <!-- ======================= PERFIL COMPRAS ======================= -->
    <section class="content-header"> 
        <h1>
            <i class="fa fa-home icon-title">Inicio</i>
        </h1>
        <ol class="breadcrumb">
            <li><a href="?module=start"><i class="fa fa-home"></i></a></li>
        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-lg-12 col-xs-12">
                <div class="alert alert-info alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <p style="font-size:15px">
                        <i class="icon fa fa-user"></i> Bienvenido/a <strong><?php echo $_SESSION['name_user'] ?></strong>
                        a la aplicación: <strong>SysWeb</strong>
                    </p>
                </div>
            </div>
        </div>

        <h2>Formulario de movimiento</h2>
        <div class="row">
            <!--Bloque de compras-->
            <div class="col-lg-6 col-xs-6">
                <div style="background-color:#27E0F5; color:#fff" class="small-box">
                    <div class="inner">
                        <p><strong>Compras</strong>
                            <ul>
                                <li>Registrar</li>
                                <li>la compra</li>
                                <li>de productos</li>
                            </ul>
                        </p>
                    </div>
                    <div class="icon">
                        <i class="glyphicon glyphicon-piggy-bank"></i>
                    </div>
                        <a href="?module=compras" class="small-box-footer" title="Registrar Compras" data-toggle="tooltip">
                            <i class="fa fa-plus"> </i></a>
                </div>
            </div>
            <!--Fin bloque de compras-->

            <!--Bloque de stock-->
            <div class="col-lg-6 col-xs-6">
                <div style="background-color:#f39c12; color:#fff" class="small-box">
                    <div class="inner">
                        <p><strong>Stock de productos</strong>
                            <ul>
                                <li>Visualizar</li>
                                <li>Stock</li>
                                <li>de productos</li>
                            </ul>
                        </p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-area-chart"></i>
                    </div>
                        <a href="?module=stock" class="small-box-footer" title="Ver Stock de productos" data-toggle="tooltip">
                            <i class="fa fa-plus"> </i></a>
                </div>
            </div>
            <!--Fin bloque de stock-->
        </div>
    </section>

<?php } elseif($_SESSION['permisos_acceso']=='Ventas'){ ?>

    <!-- ======================= PERFIL VENTAS ======================= -->
    <section class="content-header"> 
        <h1>
            <i class="fa fa-home icon-title">Inicio</i>
        </h1>
        <ol class="breadcrumb">
            <li><a href="?module=start"><i class="fa fa-home"></i></a></li>
        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-lg-12 col-xs-12">
                <div class="alert alert-info alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <p style="font-size:15px">
                        <i class="icon fa fa-user"></i> Bienvenido/a <strong><?php echo $_SESSION['name_user'] ?></strong>
                        a la aplicación: <strong>SysWeb</strong>
                    </p>
                </div>
            </div>
        </div>

        <h2>Formulario de movimiento</h2>
        <div class="row">
            <!--Bloque de ventas-->
            <div class="col-lg-6 col-xs-6">
                <div style="background-color:#12B371; color:#fff" class="small-box">
                    <div class="inner">
                        <p><strong>Ventas</strong>
                            <ul>
                                <li>Registrar</li>
                                <li>Ventas</li>
                                <li>de productos</li>
                            </ul>
                        </p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-cart-plus"></i>
                    </div>
                        <a href="?module=ventas" class="small-box-footer" title="Registrar Ventas" data-toggle="tooltip">
                            <i class="fa fa-plus"> </i></a>
                </div>
            </div>
            <!--Fin bloque de ventas-->
        </div>
    </section>

<?php } 

?>
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Mars Trading Platform</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

    <!-- Styles -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <style>
        body {
            font-family: 'Nunito', sans-serif;
            padding: 0 50px;
        }

        table {
            width: 100%;
        }

        table th {
            background-color: #ddd;
            border: 1px solid #999;
        }

        table.inner-table th {
            border: 0 !important;
        }

        table td {
            border: 1px solid #999;
        }

        table.inner-table td {
            border: 0 !important;
        }
    </style>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"
            crossorigin="anonymous"></script>
    <script src="https://unpkg.com/vue@3"></script>
</head>
<body>
<div>
    <h1 class="text-align:center;">Mars Trading Platform</h1>
    <section id="martTradingPlatformSection">
        <div class="form-group row mt-5">
            <div class="col-lg-12 mb-3">
                <h3 style="text-align: left;">
                    Martians
                    <button type="button" class="btn btn-primary" style="float:right;" data-bs-toggle="modal"
                            data-bs-target="#addMartianModal">
                        Add Martian
                    </button>
                </h3>
            </div>
            <div class="col-lg-12">
                <table>
                    <tbody>
                    <tr>
                        <th>Name</th>
                        <td v-for="martian in martians">
                            %% martian.name %%
                        </td>
                    </tr>
                    <tr>
                        <th>Age</th>
                        <td v-for="martian in martians">
                            %% martian.age %%
                        </td>
                    </tr>
                    <tr>
                        <th>Gender</th>
                        <td v-for="martian in martians">
                            %% martian.gender %%
                        </td>
                    </tr>
                    <tr>
                        <th>Trade</th>
                        <td v-for="martian in martians">
                            %% martian.trade === 1 ? 'Available' : '' %%
                            <button type="button" class="btn btn-primary" style="float: right;" :data-id="martian.id"
                                    data-bs-toggle="modal" data-bs-target="#tradeModal" @click="setMartianToTrade"
                                    v-if="martian.trade == 1">Trade
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <th>Inventory</th>
                        <td v-for="martian in martians">
                            <table class="inner-table">
                                <tr v-for="inventory in martian.inventories">
                                    <td>%% inventory.supply.name %%</td>
                                    <td>%% inventory.quantity %%</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-lg-12 mt-5 mb-3">
                <h3 style="text-align:left;">Supplies</h3>
            </div>
            <div class="col-lg-4">
                <table>
                    <thead>
                    <th>Name</th>
                    <th>Points</th>
                    </thead>
                    <tbody>
                    <tr v-for="supply in supplies">
                        <td>%% supply.name %%</td>
                        <td>%% supply.point %%</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <!-- Add Martian Modal -->
        <div class="modal fade" id="addMartianModal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <form @submit.prevent="addMartian">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLongTitle">Add Martian</h5>
                            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group row mb-2">
                                <label for="name" class="col-sm-4 col-form-label">Name</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="name" id="name"/>
                                </div>
                            </div>
                            <div class="form-group row mb-2">
                                <label for="age" class="col-sm-4 col-form-label">Age</label>
                                <div class="col-sm-8">
                                    <input type="number" class="form-control" name="age" id="age"/>
                                </div>
                            </div>
                            <div class="form-group row mb-2">
                                <label for="genderM" class="col-sm-4 col-form-label">Gender</label>
                                <div class="col-sm-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="gender" id="genderM"
                                               value="M"
                                               checked>
                                        <label class="form-check-label" for="exampleRadios1">
                                            M
                                        </label>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="gender" id="genderF"
                                               value="F">
                                        <label class="form-check-label" for="exampleRadios2">
                                            F
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row mb-2">
                                <label for="trade" class="col-sm-4 col-form-label">Trade</label>
                                <div class="col-sm-8">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="1" id="trade"
                                               name="trade">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <h5 class="col-sm-12 col-form-label">Supplies</h5>
                                <div class="col-sm-12 row mb-1" v-for="supply in supplies">
                                    <div class="col-sm-4">
                                        %% supply.name %%
                                    </div>
                                    <div class="col-sm-8">
                                        <input type="number" class="form-control supply-quantity" :data-id="supply.id"
                                               value="0">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Add</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- Trade Modal -->
        <div class="modal fade" id="tradeModal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                <div class="modal-content" v-if="martianToTrade != null">
                    <form @submit.prevent="trade">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLongTitle">Trade</h5>
                            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="alert alert-danger" v-if="tradeErrorMsg != null">
                                %% tradeErrorMsg %%
                            </div>
                            <div class="form-group row">
                                <h5 class="col-sm-6 col-form-label">Martian</h5>
                                <h5 class="col-sm-6 col-form-label">Trader</h5>
                                <div class="col-sm-6">
                                    <table>
                                        <tr>
                                            <th>Name</th>
                                            <td>
                                                %% martianToTrade.name %%
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Supplies</th>
                                            <td>
                                                <table class="inner-table">
                                                    <tr v-for="inventory in martianToTrade.inventories">
                                                        <td>%% inventory.supply.name %%</td>
                                                        <td>
                                                            <input type="number" class="form-control supplies-to-trade"
                                                                   :data-id="inventory.supply.id"
                                                                   min="0" :max="inventory.quantity"
                                                                   value="0">
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-sm-6">
                                    <table>
                                        <tr>
                                            <th>Name</th>
                                            <td>
                                                <select class="form-control" id="traderID" name="trader_id"
                                                        @change="changeTrader">
                                                    <option value="" selected>Select</option>
                                                    <template v-for="martian in martians">
                                                        <option :value="martian.id"
                                                                v-if="martian.id != martianToTrade.id">
                                                            %% martian.name %%
                                                        </option>
                                                    </template>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Supplies</th>
                                            <td>
                                                <table class="inner-table" v-if="trader != null">
                                                    <tr v-for="inventory in trader.inventories">
                                                        <td>%% inventory.supply.name %%</td>
                                                        <td>
                                                            <input type="number" class="form-control supplies-of-trader"
                                                                   :data-id="inventory.supply.id"
                                                                   min="0" :max="inventory.quantity"
                                                                   value="0">
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Trade</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>
<script type="module">
    Vue.createApp({
        delimiters: ['%%', '%%'],
        data() {
            return {
                window: window,
                martians: [],
                supplies: [],
                martianToTrade: null,
                trader: null,
                tradeErrorMsg: null
            }
        },
        mounted() {
            this.getMartians();
            this.getSupplies();
        },
        methods: {
            getMartians: function () {
                var _this = this;
                $.ajax({
                    url: '/api/martians',
                    method: 'GET',
                    dataType: "json",
                    success: function (data, textStatus, jqXHR) {
                        if (data.success) {
                            _this.martians = data.data.martians;
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown) {

                    }
                });
            },
            getSupplies: function () {
                var _this = this;
                $.ajax({
                    url: '/api/supplies',
                    method: 'GET',
                    dataType: "json",
                    success: function (data, textStatus, jqXHR) {
                        if (data.success) {
                            _this.supplies = data.data.supplies;
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown) {

                    }
                });
            },
            addMartian: function (e) {
                var _this = this;
                var form = e.currentTarget;
                var params = {
                    name: $(form).find("[name=name]").val(),
                    age: $(form).find("[name=age]").val(),
                    gender: $(form).find("[name=gender]").val(),
                    trade: ($(form).find("[name=trade]").prop("checked") ? 1 : 0)
                };

                var supplies = [];
                $(".supply-quantity").each(function (idx) {
                    if ($(this).val() > 0) {
                        supplies.push({
                            id: $(this).data('id'),
                            quantity: $(this).val()
                        })
                    }
                });
                params.supplies = supplies;

                $.ajax({
                    url: '/api/martians',
                    method: 'POST',
                    data: JSON.stringify(params),
                    dataType: "json",
                    contentType: "application/json; charset=utf-8",
                    success: function (data, textStatus, jqXHR) {
                        alert(data.message);
                        if (data.success) {
                            _this.getMartians();
                            $("#addMartianModal").find("button[data-bs-dismiss=modal]").trigger("click");
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        _this.tradeErrorMsg = jqXHR.responseJSON.message;
                    }
                })
            },
            setMartianToTrade: function (e) {
                var _this = this;
                var btn = e.currentTarget;
                var martianID = $(btn).data("id");
                $.ajax({
                    url: '/api/martians/' + martianID,
                    method: 'GET',
                    success: function (data, textStatus, jqXHR) {
                        if (data.success) {
                            _this.martianToTrade = data.data;
                        } else {
                            alert(data.message);
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown) {

                    }
                })
            },
            changeTrader: function (e) {
                var ele = e.currentTarget;
                var traderID = $(ele).val();
                var trader = null;
                for (var i = 0; i < this.martians.length; i++) {
                    if (this.martians[i].id == traderID) {
                        trader = this.martians[i];
                    }
                }
                this.trader = trader;
            },
            trade: function (e) {
                var _this = this;
                var form = e.currentTarget;
                var supplies = [], suppliesOfTrader = [];
                $(".supplies-to-trade").each(function (idx) {
                    var supplyID = $(this).data("id");
                    var quantity = $(this).val();
                    supplies.push({
                        id: supplyID,
                        quantity: quantity
                    });
                });
                $(".supplies-of-trader").each(function (idx) {
                    var supplyID = $(this).data("id");
                    var quantity = $(this).val();
                    suppliesOfTrader.push({
                        id: supplyID,
                        quantity: quantity
                    });
                });

                var params = {
                    supplies: supplies,
                    trader_id: $("#traderID").val(),
                    supplies_of_trader: suppliesOfTrader
                };

                $.ajax({
                    url: '/api/martians/' + _this.martianToTrade.id + '/trade',
                    method: 'POST',
                    data: JSON.stringify(params),
                    dataType: "json",
                    contentType: "application/json; charset=utf-8",
                    success: function (data, textStatus, jqXHR) {
                        if (data.success) {
                            alert(data.message);
                            _this.getMartians();
                            _this.martianToTrade = null;
                            _this.trader = null;
                            $("#tradeModal").find("button[data-bs-dismiss=modal]").trigger("click");
                        } else {
                            _this.tradeErrorMsg = data.message;
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        _this.tradeErrorMsg = jqXHR.responseJSON.message;
                    }
                })
            }
        }
    }).mount('#martTradingPlatformSection');
</script>
</body>
</html>

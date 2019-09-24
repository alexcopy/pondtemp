<template>
    <div class="container">
        <div class="row">
            <h4>Light Pellets</h4>
            <div class="col-lg-1">
            <span>
               <button @click="feed('pellets')" class="btn btn-success">
                   <i class="glyphicon glyphicon-plus"></i>
               </button>
            </span>
            </div>
            <div class="col-lg-1">
            <span>
               <button @click="delFeed('pellets')" class="btn btn-success">
                   <i class="glyphicon glyphicon-minus"></i>
               </button>
            </span>
            </div>
        </div>
        <h5>Today: <b class="text-danger">{{feeds['pellets']}}</b> scoops</h5>

        <div class="row">
            <h4>Sink Pellets</h4>
            <div class="col-lg-1">
                <span>
                    <button @click="feed('sinkpellets')" class="btn btn-warning">
                    <i class="glyphicon glyphicon-plus"></i>
                    </button>
                </span>
            </div>
            <div class="col-lg-1">
                <span>
                    <button @click="delFeed('sinkpellets')" class="btn btn-warning">
                    <i class="glyphicon glyphicon-minus"></i>
                    </button>
                </span>
            </div>
        </div>
        <h5>Today: <b class="text-danger">{{feeds['sinking']}}</b> scoops</h5>

        <div v-for="pond in  ponds" :key="'pondid_'+pond.id">
            <input
                    :value="pond.tankName"
                    :id="'pondid_'+pond.id"
                    :name="pond.tankName"
                    v-model="pondId"
                    type="radio"
            />
            <label :for="'pondid_'+pond.id">{{ pond.tankName }}</label>
        </div>
        <br/>
        <br/>

        <table class="table table-condensed">
            <thead>
            <tr>
                <th>Date</th>
                <th>Food</th>
                <th>scoops</th>
                <th>weight</th>
                <th>total</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="(food, index) in sortedTableData">
                <td><b>{{index}}</b></td>
                <td>
                    <div>pellets</div>
                    <div>sinkpell</div>
                </td>
                <td>
                    <div>{{food.pellets.count}}</div>
                    <div>{{food.sinkpellets.count}}</div>
                </td>
                <td>
                    <div>{{food.pellets.weight}} g</div>
                    <div>{{food.sinkpellets.weight}} g</div>
                </td>
                <td>
                    <div><b>{{food.pellets.weight + food.sinkpellets.weight}} g </b></div>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
    import FormMixin from '../feed';

    export default {
        mixins: [FormMixin],
    }
</script>

<style>
    .glyphicon {
        font-size: 50px;
    }

    h5, .h5 {
        font-size: 24px;
    }
</style>
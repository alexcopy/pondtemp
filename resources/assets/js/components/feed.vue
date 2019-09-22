<template>
    <div class="container">
        <div class="row">
            <div class="col-lg-4">
            <span>
                <h4>Light Pellets</h4>
               <button @click="feed('pellets')" class="btn btn-success">
                   <i class="glyphicon glyphicon-plus"></i>
               </button>
            </span>
            </div>
        </div>
        <h5>Today: <b class="text-danger">{{feeds['pellets']}}</b> scoops</h5>

        <div class="row">
            <div class="col-lg-4">
                <span>
                    <h4>Sink Pellets</h4>
                    <button @click="feed('sinkpellets')" class="btn btn-warning">
                    <i class="glyphicon glyphicon-plus"></i>
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

        <div class="row">
            <p>Total pellets: 500</p>
            <p>Total pellets: 500</p>
        </div>

        <table class="table table-condensed">
            <thead>
            <tr>
                <th></th>
                <th></th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td></td>
                <td></td>
                <td></td>
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
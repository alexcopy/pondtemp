<template>
    <div class="modal fade" id="addMeter" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h5 class="modal-title" id="exampleModalLabel">
                        <slot name="header"></slot>
                    </h5>
                </div>
                <div class="modal-body">
                    <slot></slot>
                    <form @submit.prevent="submit">

                        <div class="form-group">
                            <label for="pond_id">Pond ID:</label>
                            <select class='form-control' v-model='fields.pond_id' @change='getPonds()' name="pond_id" id="pond_id">
                                <option value='0' >Select meter ID</option>
                                <option v-for='data in ponds' :value='data.id'>{{ data.deviceName }}</option>
                            </select>
                            <div v-if="errors && errors.pond_id" class="text-danger">{{ errors.pond_id[0] }}</div>
                        </div>


                        <div class="form-group">
                            <label for="typeName">Type Name:</label>
                            <input type="text" class="form-control" name="typeName" id="typeName"
                                   v-model="fields.typeName" autocomplete="off" />
                            <div v-if="errors && errors.typeName" class="text-danger">{{ errors.typeName[0] }}</div>
                        </div>

                        <div class="form-group">
                            <label for="description">Description:</label>
                            <textarea class="form-control" id="description" name="description" rows="5"
                                      v-model="fields.description"></textarea>
                            <div v-if="errors && errors.description" class="text-danger">{{ errors.description[0] }}</div>
                        </div>
                    </form>
                </div>
                <div slot="modal-footer" class="pull-right">
                    <slot name="footer" class="pull-right">
                        <p slot="footer">
                            <button  type="submit" class="btn btn-primary"  @click="submit" >Save changes</button>
                            <button type="button" class="btn  btn-danger" data-dismiss="modal">Close</button>
                        </p>
                    </slot>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    import FormMixin from '../MixDeviceTypes';

    export default {
        mixins: [FormMixin],

        data() {
            return {
                'action': '/pond/devices',
            }
        }
    }
</script>
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
                            <label for="tankName">Tank Name:</label>
                            <input type="text" class="form-control" name="tankName" id="tankName"
                                   v-model="fields.tankName" autocomplete="off" />
                            <div v-if="errors && errors.tankName" class="text-danger">{{ errors.tankName[0] }}</div>
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
    import FormMixin from '../MixTanks';

    export default {
        mixins: [FormMixin],
        data() {
            return {
                'action': '/pond/tanks',
            }
        }
    }
</script>
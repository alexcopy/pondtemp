Vue.component('modal', {
    template: `
        <div class="modal fade" id="addMeter" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
              </div>
              <div class="modal-footer">
              <slot name="footer"></slot>
              </div>
            </div>
          </div>
        </div>
    `
});



new Vue({
    el: '#rootdiv'
});

<div id="confirmationModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <h4>Confirmaion Modal!</h4>
                <form action="" method="POST">
                    @csrf
                    <p class="question"></p>
                    <div class="mb-3 text-end">
                        <button class="btn btn-primary" type="submit">Yes</button>
                        <button type="button" class="btn btn-dark" data-bs-dismiss="modal">No</button>
                    </div>
                </form>
            </div>
        </div>
    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->

@push('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            (function($) {
                "use strict";

                $(document).on('click', '.confirmationBtn', function() {
                    var modal = $('#confirmationModal');
                    let data = $(this).data();
                    modal.find('.question').text(`${data.question}`);
                    modal.find('form').attr('action', `${data.action}`);
                    modal.modal('show');
                });

            })(jQuery);
        });
    </script>
@endpush

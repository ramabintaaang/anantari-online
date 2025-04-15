<script>
    $(document).ready(function() {

        $('.selectAll_new').click(function() {
            $('input[name="listFormBahan[]"]').prop('checked', this.checked);
            // alert("woi")
        });

        // Ensure the select all checkbox is checked/unchecked based on individual checkboxes
        $('input[name="listFormBahan[]"]').change(function() {
            if ($('input[name="listFormBahan[]"]').length == $('input[name="listFormBahan[]"]:checked')
                .length) {
                $('.selectAll_new').prop('checked', true);
            } else {
                $('.selectAll_new').prop('checked', false);
            }
        });


        $('#getCheckedValues').click(function() {
            var checkedValues = [];
            $('#formBahan input[type="checkbox"]:checked').each(function() {
                checkedValues.push($(this).attr(
                    'id')); // atau $(this).val() jika menggunakan nilai dari checkbox
            });
            console.log(checkedValues);
            // Anda bisa menampilkan alert atau melakukan sesuatu dengan checkedValues
            alert("Checked values: " + checkedValues.join(", "));
        });

    });
</script>

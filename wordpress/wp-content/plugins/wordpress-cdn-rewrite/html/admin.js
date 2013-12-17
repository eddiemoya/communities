jQuery(document).ready(function($){
    //add a new row for the domain whitelist
    $("#addDomain").click(function() {
        var rowCount = $('[id^="domain"]').length;
        var row = $('#domain0').clone();

        //update the attributes of the input in our row
        row.find('input').val('');

        //
        while($('#domain' + rowCount).length) {
            rowCount++;
        }

        //also update the rows id
        row = row.attr('id', 'domain' + rowCount);
        row.insertBefore('#addDomainRow');
    });

    $("#addRule").click(function() {
        var rowCount = $('[id^="rule"]').length;
        var row = $('#rule0').clone();

        //update the attributes of the input in our row
        row.find('input:not(.button-primary)').val('');

        //
        while($('#rule' + rowCount).length) {
            rowCount++;
        }

        row.find('[name="wpcdnrewrite-rules[0][rule]"]').attr('name', 'wpcdnrewrite-rules['+rowCount+'][rule]');
        row.find('[name="wpcdnrewrite-rules[0][type]"]').attr('name', 'wpcdnrewrite-rules['+rowCount+'][type]');
        row.find('[name="wpcdnrewrite-rules[0][match]"]').attr('name', 'wpcdnrewrite-rules['+rowCount+'][match]');


        //also update the rows id
        row = row.attr('id', 'rule' + rowCount);
        row.insertBefore('#addRuleRow');
    });
});


function removeRow(element) {
    jQuery(element).parent().parent().remove();
}
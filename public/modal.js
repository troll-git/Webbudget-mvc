/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$(document).ready(function() {
$('#balanceRange').change(function() {
    var opval = $(this).val();
    if(opval=="ns"){
        $('#niestandardoweModal').modal("show");
    }
});
});

$(document).ready(function(){        
    $('#balanceModal').modal('show');
     }); 

//edit-delete
function editDelete(){
    $('.expense-settings').each(function (i, obj) {
      $(obj).find(".save-expense").click(function () {
        var name = $(obj).find(".form-expense").val();
        var id = $(obj).find(".expense-id").html();

        var data = {
          name: name,
          id: id,
        }
        $.ajax({
          type: "POST",
          url: "/ajax/changeExpenses",
          data: data,
          dataType: "json",
          success: function (json) { /*Funkcja zawiera parametr*/
            $(obj).find(".expense-name").html(json.name);
            $(obj).find(".form-expense").val(json.name);
            var message="Czy na pewno chcesz usunac "+json.name+"?";
            $(obj).find(".form-group").find('h2').html(message);
            $(obj).find('.modal-expense-edit').modal('hide');
          },
          error: function (blad) {
            alert("errors");
            console.log(blad);
          }
        })
      })
      //usuwanie kategorii przychodu
      $(obj).find(".delete-expense").click(function () {
        var name = $(obj).find(".expense-name").html();
        var data = {
          name: name,
        }
        $.ajax({
          type: "POST",
          url: "/ajax/removeExpenseCat",
          data: data,
          dataType: "json",
          success: function (json) {
            
            if ($(obj).find(".expense-name").html() == json) {
              $(obj).hide();
            }
            $('.modal-expense-delete').modal('hide');
          },
          error: function (blad) {
            alert("errors");
            console.log(blad);
          }
        })

      })
    })
  }

  $(document).ready(function () {
    editDelete();
  

//dodanie nowej kategorii
$("#submit-new-expense-cat").click(function(){
  var name=$("#form-add-expense-cat").val();
  var data={
          name:name,
          }
          
          $.ajax({
            type:"POST", 
            url: "/ajax/addExpenseCat",
            data: data,
            dataType: "json",
                success: function(json) { 
                      //$(obj).find(".income-name").html(json.name);
                      //alert(json);
                      
                      var $newExpenseCategory = $(".expense-settings").last().clone();
                      $newExpenseCategory.find(".expense-id").html(json);
                      $newExpenseCategory.find(".expense-name").html(name);
                      $newDt= "editExpenseCatModal"+ json;
                      $newExpenseCategory.find(".edit-expense-cat").attr("data-target",('#'+$newDt));
                      $newExpenseCategory.find(".modal-expense-edit").attr("id",$newDt);
                      $newExpenseCategory.find(".delete-expense-cat").attr("data-target",('#editDeleteCatModal'+json));
                      $newExpenseCategory.find(".modal-expense-delete").attr("id",('editDeleteCatModal'+json));
                      //var newId="editIncomeCatModal"+json;
                      
                      $newExpenseCategory.find(".form-expense").val(name);
                      var message="Czy na pewno chcesz usunac "+name+"?";
                      $newExpenseCategory.find(".form-group").find('h2').html(message);
                      //$newIncomeCategory.find(".edit-income-cat").attr("id","newId");
                      $(".expense-settings").last().append($newExpenseCategory); 
                      $('.modal-expense-add').modal('hide');
                      editDelete();
                },
                error: function(blad) {
                    alert( "errors");
                    console.log(blad);
                }
        })
})

});
function editDelete(){
    $('.income-settings').each(function (i, obj) {
      $(obj).find(".save-income").click(function () {
        var name = $(obj).find(".form-income").val();
        var id = $(obj).find(".income-id").html();

        var data = {
          name: name,
          id: id,
        }
        $.ajax({
          type: "POST",
          url: "/ajax/changeIncomes",
          data: data,
          dataType: "json",
          success: function (json) { /*Funkcja zawiera parametr*/
            $(obj).find(".income-name").html(json.name);
            $(obj).find(".form-income").val(json.name);
            var message="Czy na pewno chcesz usunac "+json.name+"?";
            $(obj).find(".form-group").find('h2').html(message);
            $(obj).find('.modal-income-edit').modal('hide');
          },
          error: function (blad) {
            alert("errors");
            console.log(blad);
          }
        })
      })
      //usuwanie kategorii przychodu
      $(obj).find(".delete-income").click(function () {
        var name = $(obj).find(".income-name").html();
        var data = {
          name: name,
        }
        $.ajax({
          type: "POST",
          url: "/ajax/removeIncomeCat",
          data: data,
          dataType: "json",
          success: function (json) {
            
            if ($(obj).find(".income-name").html() == json) {
              $(obj).hide();
            }
            $('.modal-income-delete').modal('hide');
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
$("#submit-new-income-cat").click(function(){
  var name=$("#form-add-income-cat").val();
  var data={
          name:name,
          }
          
          $.ajax({
            type:"POST", 
            url: "/ajax/addIncomeCat",
            data: data,
            dataType: "json",
                success: function(json) { 
                      //$(obj).find(".income-name").html(json.name);
                      //alert(json);
                      
                      var $newIncomeCategory = $(".income-settings").last().clone();
                      $newIncomeCategory.find(".income-id").html(json);
                      $newIncomeCategory.find(".income-name").html(name);
                      $newDt= "editIncomeCatModal"+ json;
                      $newIncomeCategory.find(".edit-income-cat").attr("data-target",('#'+$newDt));
                      $newIncomeCategory.find(".modal-income-edit").attr("id",$newDt);
                      $newIncomeCategory.find(".delete-income-cat").attr("data-target",('#editDeleteCatModal'+json));
                      $newIncomeCategory.find(".modal-income-delete").attr("id",('editDeleteCatModal'+json));
                      //var newId="editIncomeCatModal"+json;
                      
                      $newIncomeCategory.find(".form-income").val(name);
                      var message="Czy na pewno chcesz usunac "+name+"?";
                      $newIncomeCategory.find(".form-group").find('h2').html(message);
                      //$newIncomeCategory.find(".edit-income-cat").attr("id","newId");
                      $(".income-settings").last().append($newIncomeCategory); 
                      $('.modal-income-add').modal('hide');
                      editDelete();
                },
                error: function(blad) {
                    alert( "errors");
                    console.log(blad);
                }
        })
})

});
//edit-delete
function editDeleteExpenses() {
  $('.expense-settings').each(function (i, obj) {
    //enable limit

    $(obj).find("#enable-lim").click(function () {
      if ($(this).prop("checked") == true) {
        $(obj).find("#form-expense-lim").prop('disabled', false);
        $(obj).find("#form-expense-lim").val() == "";

      }
      else if ($(this).prop("checked") == false) {
        $(obj).find("#form-expense-lim").prop('disabled', true);
      }
    })
    //
    $(obj).find(".save-expense").click(function () {
      if ($(obj).find("#form-expense-lim").val() == "" && $(obj).find("#form-expense-lim").prop('disabled') == false) {
        alert('Pole nie moze byc puste');
      }
      else {
        var name = $(obj).find(".form-expense").val();
        var id = $(obj).find(".expense-id").html();
        var limit = $(obj).find("#form-expense-lim").val();
        var data = {
          name: name,
          id: id,
          limit: limit,
        }
        $.ajax({
          type: "POST",
          url: "/ajax/changeExpenses",
          data: data,
          dataType: "json",
          success: function (json) { 
            $(obj).find(".expense-name").html(json.name);
            $(obj).find(".form-expense").val(json.name);
            var message = "Czy na pewno chcesz usunac " + json.name + "?";
            $(obj).find(".form-group").find('h2').html(message);
            $(obj).find('.modal-expense-edit').modal('hide');
            //add limit tag
            $(obj).find('.limit-notification').html('Limit: ' + limit);
            $(obj).find(".limit-notification").show();


          },
          error: function (blad) {
            alert("errors");
            console.log(blad);
          }
        })
      }




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
  //check limits
  $(".expense-settings").each(function (i, obj) {
    if ($(obj).find(".limit-notification").html() == "Limit: ") {
      $(obj).find(".limit-notification").hide();

    }
    else {
      $(obj).find("#enable-lim").prop('checked', true);
    }
  })
  editDeleteExpenses();


  //dodanie nowej kategorii
  $("#submit-new-expense-cat").click(function () {
    var name = $("#form-add-expense-cat").val();
    var data = {
      name: name,
    }

    $.ajax({
      type: "POST",
      url: "/ajax/addExpenseCat",
      data: data,
      dataType: "json",
      success: function (json) {
        var $newExpenseCategory = $(".expense-settings").last().clone();
        $newExpenseCategory.find(".expense-id").html(json);
        $newExpenseCategory.find(".expense-name").html(name);
        $newDt = "editExpenseCatModal" + json;
        $newExpenseCategory.find(".edit-expense-cat").attr("data-target", ('#' + $newDt));
        $newExpenseCategory.find(".modal-expense-edit").attr("id", $newDt);
        $newExpenseCategory.find(".delete-expense-cat").attr("data-target", ('#editDeleteCatModal' + json));
        $newExpenseCategory.find(".modal-expense-delete").attr("id", ('editDeleteCatModal' + json));

        $newExpenseCategory.find(".form-expense").val(name);
        var message = "Czy na pewno chcesz usunac " + name + "?";
        $newExpenseCategory.find(".form-group").find('h2').html(message);
        $(".expense-settings").last().append($newExpenseCategory);
        $('.modal-expense-add').modal('hide');
        editDeleteExpenses();
      },
      error: function (blad) {
        alert("errors");
        console.log(blad);
      }
    })
  })

});
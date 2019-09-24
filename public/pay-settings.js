//edit-delete
function editDeletePay() {
  $('.pay-settings').each(function (i, obj) {
    $(obj).find(".save-pay").click(function () {
      var name = $(obj).find(".form-pay").val();
      var id = $(obj).find(".pay-id").html();

      var data = {
        name: name,
        id: id,
      }
      $.ajax({
        type: "POST",
        url: "/ajax/changePay",
        data: data,
        dataType: "json",
        success: function (json) { /*Funkcja zawiera parametr*/
          $(obj).find(".pay-name").html(json.name);
          $(obj).find(".form-pay").val(json.name);
          var message = "Czy na pewno chcesz usunac " + json.name + "?";
          $(obj).find(".form-group").find('h2').html(message);
          $(obj).find('.modal-pay-edit').modal('hide');
        },
        error: function (blad) {
          alert("errors");
          console.log(blad);
        }
      })
    })
    //usuwanie kategorii platnosci
    $(obj).find(".delete-pay").click(function () {
      var name = $(obj).find(".pay-name").html();
      var data = {
        name: name,
      }
      $.ajax({
        type: "POST",
        url: "/ajax/removePayCat",
        data: data,
        dataType: "json",
        success: function (json) {

          if ($(obj).find(".pay-name").html() == json) {
            $(obj).hide();
          }
          $('.modal-pay-delete').modal('hide');
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
  editDeletePay();


  //dodanie nowej kategorii
  $("#submit-new-pay-cat").click(function () {
    var name = $("#form-add-pay-cat").val();
    var data = {
      name: name,
    }

    $.ajax({
      type: "POST",
      url: "/ajax/addPayCat",
      data: data,
      dataType: "json",
      success: function (json) {
        var $newPayCategory = $(".pay-settings").last().clone();
        $newPayCategory.find(".pay-id").html(json);
        $newPayCategory.find(".pay-name").html(name);
        $newDt = "editPayCatModal" + json;
        $newPayCategory.find(".edit-pay-cat").attr("data-target", ('#' + $newDt));
        $newPayCategory.find(".modal-pay-edit").attr("id", $newDt);
        $newPayCategory.find(".delete-pay-cat").attr("data-target", ('#deletePayCatModal' + json));
        $newPayCategory.find(".modal-pay-delete").attr("id", ('deletePayCatModal' + json));
        $newPayCategory.find(".form-pay").val(name);
        var message = "Czy na pewno chcesz usunac " + name + "?";
        $newPayCategory.find(".form-group").find('h2').html(message);
        $(".pay-settings").last().append($newPayCategory);
        $('.modal-pay-add').modal('hide');
        editDeletePay();
      },
      error: function (blad) {
        alert("errors");
        console.log(blad);
      }
    })
  })

});
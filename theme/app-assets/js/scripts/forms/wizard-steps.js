/*=========================================================================================
    File Name: wizard-steps.js
    Description: wizard steps page specific js
    ----------------------------------------------------------------------------------------
    Item Name: Robust - Responsive Admin Template
    Version: 2.1
    Author: PIXINVENT
    Author URL: http://www.themeforest.net/user/pixinvent
==========================================================================================*/

// Wizard tabs with numbers setup
$(".number-tab-steps").steps({
  headerTag: "h6",
  bodyTag: "fieldset",
  transitionEffect: "fade",
  titleTemplate: '<span class="step">#index#</span> #title#',
  labels: {
    finish: "Submit",
  },
  onFinished: function (event, currentIndex) {
    alert("Form submitted.");
  },
});

// Wizard tabs with icons setup
$(".icons-tab-steps").steps({
  headerTag: "h6",
  bodyTag: "fieldset",
  transitionEffect: "fade",
  titleTemplate: '<span class="step">#index#</span> #title#',
  labels: {
    finish: "Submit",
  },
  onFinished: function (event, currentIndex) {
    let cus_reg_nme = document.getElementById("cus_reg_nme").value;
    let cus_tg_cust = document.getElementById("cus_tg_cust1").value; 
    //let cusd_obj = document.getElementById('cusd_obj').value;
    formname = "frm_cust_add";
    if(cus_reg_nme==""){
      Swal.fire({
        title: "Error!",
        html:
          "ไม่สามารถบันทึกข้อมูลได้ เนื่องจากยังไม่ได้ระบุชื่อจดทะเบียน" +
          "\n" +
          "กรุณาตรวจสอบอีกครั้ง!",
        type: "error",
        confirmButtonClass: "btn btn-danger",
        buttonsStyling: false,
      });
    };
    

    $.ajax({
      beforeSend: function () {
        $("body").append(
          '<div id="requestOverlay" class="request-overlay"></div>'
        );
        $("#requestOverlay").show();
      },
      type: "POST",
      url: "../serverside/n_newcust_post.php",
      data: $("#" + formname).serialize(),
      //data: formData,
      timeout: 100000,
      error: function (xhr, error) {
        showmsg("[" + xhr + "] " + error);
      },
      success: function (result) {
        //alert(result);
        var json = $.parseJSON(result);
        //consolealert(json.r);
        if (json.r == "0") {
          clearloadresult();
          Swal.fire({
            title: "Error!",
            html: json.e,
            type: "error",
            confirmButtonClass: "btn btn-danger",
            buttonsStyling: false,
          });
        } else {
          clearloadresult();
          Swal.fire({
            type: "success",
            title: "Successful",
            showConfirmButton: false,
            timer: 50000,
            confirmButtonClass: "btn btn-primary",
            buttonsStyling: false,
            animation: false,
          });
          location.reload(true);
          $(location).attr("href", "../newcust/newcust_edit.php?q=" + json.nb);
        }
      },
      complete: function () {
        $("#requestOverlay").remove();
      },
    });
  },
});

// Vertical tabs form wizard setup
/* $(".vertical-tab-steps").steps({
    headerTag: "h6",
    bodyTag: "fieldset",
    transitionEffect: "fade",
    stepsOrientation: "vertical",
    titleTemplate: '<span class="step">#index#</span> #title#',
    labels: {
        finish: 'Submit'
    },
    onFinished: function (event, currentIndex) {
        alert("Form submitted.");
    }
}); */

// Validate steps wizard

// Show form
var form = $(".steps-validation").show();

/* $(".steps-validation").steps({
    headerTag: "h6",
    bodyTag: "fieldset",
    transitionEffect: "fade",
    titleTemplate: '<span class="step">#index#</span> #title#',
    labels: {
        finish: 'Submit'
    },
    onStepChanging: function (event, currentIndex, newIndex)
    {
        // Allways allow previous action even if the current form is not valid!
        if (currentIndex > newIndex)
        {
            return true;
        }
        // Forbid next action on "Warning" step if the user is to young
        if (newIndex === 3 && Number($("#age-2").val()) < 18)
        {
            return false;
        }
        // Needed in some cases if the user went back (clean up)
        if (currentIndex < newIndex)
        {
            // To remove error styles
            form.find(".body:eq(" + newIndex + ") label.error").remove();
            form.find(".body:eq(" + newIndex + ") .error").removeClass("error");
        }
        form.validate().settings.ignore = ":disabled,:hidden";
        return form.valid();
    },
    onFinishing: function (event, currentIndex)
    {
        form.validate().settings.ignore = ":disabled";
        return form.valid();
    },
    onFinished: function (event, currentIndex)
    {
        alert("Submitted!");
    }
}); */

// Initialize validation
/* $(".steps-validation").validate({
    ignore: 'input[type=hidden]', // ignore hidden fields
    errorClass: 'danger',
    successClass: 'success',
    highlight: function(element, errorClass) {
        $(element).removeClass(errorClass);
    },
    unhighlight: function(element, errorClass) {
        $(element).removeClass(errorClass);
    },
    errorPlacement: function(error, element) {
        error.insertAfter(element);
    },
    rules: {
        email: {
            email: true
        }
    }
}); */

// Initialize plugins
// ------------------------------

// Date & Time Range
/* $('.datetime').daterangepicker({
    timePicker: true,
    timePickerIncrement: 30,
    locale: {
        format: 'MM/DD/YYYY h:mm A'
    }
}); */

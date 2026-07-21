$.validator.setDefaults({
    submitHandler: function () {
        alert("submitted!");
    }
});

$(document).ready(function () {
    $.validator.addMethod("alphabetsnspace", function (value, element) {
        return this.optional(element) || /^[a-zA-Z ]*$/.test(value);
    });

    $.validator.addMethod("myEmail", function (value, element) {
        if(value !=''){
        return this.optional(element) || (/^[a-z0-9]+([-._][a-z0-9]+)*@([a-z0-9]+(-[a-z0-9]+)*\.)+[a-z]{2,4}$/.test(value) && /^(?=.{1,64}@.{4,64}$)(?=.{6,100}$).*/.test(value));
        }else{
            return true;
        }
    }, 'Please enter valid email address.');
    // custom code for greater than
    $.validator.addMethod('greaterThan', function (value, element, param) {
        var $otherElement = $("#course_fee");
        return (value <= jQuery($otherElement).val());
    }, 'Must be not greater than course fee');


    $.validator.addMethod("DateFormat", function (value, element) {
        return value.match(/^(0[1-9]|[12][0-9]|3[01])[- //.](0[1-9]|1[012])[- //.](19|20)\d\d$/);
        // return value.match(/^(0[1-9]|1[012])[- //.](0[1-9]|[12][0-9]|3[01])[- //.](19|20)\d\d$/);
    },
            "Please enter a date in the format dd/mm/yyyy"
            );

    jQuery.validator.addMethod("phoneUS", function (phone_number, element) {
        phone_number = phone_number.replace(/\s+/g, "");
        return this.optional(element) || phone_number.length > 9 &&
                phone_number.match(/^(\+?1-?)?(\([0-9]\d{2}\)|[0-9]\d{2})-?[0-9]\d{2}-?\d{4}$/);
    }, "Please specify a valid phone number");

    $.validator.addMethod("minAge", function (value, element, min) {
        var value1 = value;

        var date = value;
        var d = new Date(date.split("/").reverse().join("-"));

        var dd = d.getDate();
        var mm = d.getMonth() + 1;
        var yy = d.getFullYear();

        value1 = mm + "/" + dd + "/" + yy;

        var today = new Date();
        var birthDate = new Date(value1);
        //var birthDate = new Date( value.getMonth(), value.getDate(),value.current_year);
        var age = today.getFullYear() - birthDate.getFullYear();

        if (age > min + 1) {
            return true;
        }
        var m = today.getMonth() - birthDate.getMonth();
        if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
            age--;
        }

        return age >= min;
    }, "You are not old enough!");


    // custom code for lesser than
    /*$.validator.addMethod('lesserThan', function(value, element, param) {
     return ( value < jQuery(param).val() );
     }, 'Must be less than end' );*/

    $("#signupForm").validate({
        rules: {
            student_name: {
                required: true,
//                        alphabetsnspace: true

            },
            dob: {
                required: true,
                DateFormat: true,
                minAge: 10


            },

            time_of: "required",
            time_of_2: "required",
            gender: "required",
            n_o_shots: "required",
            n_o_shots2: "required",
            hit_score: "required",
            hit_score2: "required",
            h_d_y_h_a_u: "required",
            fb: "required",
            before_shooting: "required",
            email: {
               // required: true,
                myEmail:true
            },
            contact1: {
                required: true,
                phoneUS: true
            },

            are_you: "required",
            name_s_c: {
                required: true
            },
            other_specify: "required",
            attended_by: "required",
            vip: "required",
            vip_remark: "required",
            remark: "required",
            reason: "required"

        },
        messages: {
            student_name: {
                required: "Please enter your student name",
//                        alphabetsnspace:"Only Alphabet",

            },
            dob: {
                required: "Please enter you date of birth.",
                minAge: "You must be at least 10 years old!"
            },

            date_of: "Please enter date",
            time_of: "Please select time slot",
            time_of_2: "Please select time slot",
            gender: "Please select gender",
            n_o_shots: "This field is required",
            n_o_shots2: "This field is required",
            hit_score: "This field is required",
            hit_score2: "This field is required",
            h_d_y_h_a_u: "Please select how did you hear",
            fb: "are you on facebook required",
            before_shooting: "have you done shooting before required",
            email: "Please enter a valid email address",
            contact1: {
                required: "Please enter contact number",
                minlength: "Enter 10 digits Mobile No"

            },
            other_specify: "Please enter other specify",
            attended_by: "Please enter attended by",
            vip: "Please select vip status",
            vip_remark: "Please enter vip_remark",
            remark: "Please enter remark",
            reason: "Please enter reason",
            name_s_c: {
                required: "this field is required",
                alphabetsnspace: "Only characters required",
            },
            /*username1: {
             required: "Please enter a username",
             minlength: "Your username must consist of at least 2 characters"
             },
             password1: {
             required: "Please provide a password",
             minlength: "Your password must be at least 5 characters long"
             },
             confirm_password1: {
             required: "Please provide a password",
             minlength: "Your password must be at least 5 characters long",
             equalTo: "Please enter the same password as above"
             },
             email1: "Please enter a valid email address",
             agree1: "Please accept our policy"*/
        },
        errorElement: "em",
        errorPlacement: function (error, element) {
            // Add the `help-block` class to the error element
            error.addClass("help-block");

            // Add `has-feedback` class to the parent div.form-group
            // in order to add icons to inputs
            element.parents(".col-md-4").addClass("has-feedback");

            if (element.prop("type") === "checkbox") {
                error.insertAfter(element.parent("label"));
            } else {
                error.insertAfter(element);
            }

            // Add the span element, if doesn't exists, and apply the icon classes to it.
            if (!element.next("span")[ 0 ]) {
                $("<span class='glyphicon glyphicon-remove form-control-feedback'></span>").insertAfter(element);
            }
        },
        success: function (label, element) {
            // Add the span element, if doesn't exists, and apply the icon classes to it.
            if (!$(element).next("span")[ 0 ]) {
                $("<span class='glyphicon glyphicon-ok form-control-feedback'></span>").insertAfter($(element));
            }
        },
        highlight: function (element, errorClass, validClass) {
            $(element).parents(".col-md-4").addClass("has-error").removeClass("has-success");
            $(element).next("span").addClass("glyphicon-remove").removeClass("glyphicon-ok");
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).parents(".col-md-4").addClass("has-success").removeClass("has-error");
            $(element).next("span").addClass("glyphicon-ok").removeClass("glyphicon-remove");
        }
       
    });
    $("#signupForm2").validate({
        rules: {
            student_name: {
                required: true,
//                        alphabetsnspace: true
            },

            contact1: {
                required: true,
                phoneUS: true

            },
            remark: "required",
            reason: "required"

        },
        messages: {
            student_name: {
                required: "Please enter your student name",
                alphabetsnspace: "Only characters required"
            },
            contact1: "Please enter contact number",
            attended_by: "Please enter attended by",
            remark: "Please enter remark",
            reason: "Please enter reason"

        },
        errorElement: "em",
        errorPlacement: function (error, element) {
            // Add the `help-block` class to the error element
            error.addClass("help-block");

            // Add `has-feedback` class to the parent div.form-group
            // in order to add icons to inputs
            element.parents(".col-md-4").addClass("has-feedback");

            if (element.prop("type") === "checkbox") {
                error.insertAfter(element.parent("label"));
            } else {
                error.insertAfter(element);
            }

            // Add the span element, if doesn't exists, and apply the icon classes to it.
            if (!element.next("span")[ 0 ]) {
                $("<span class='glyphicon glyphicon-remove form-control-feedback'></span>").insertAfter(element);
            }
        },
        success: function (label, element) {
            // Add the span element, if doesn't exists, and apply the icon classes to it.
            if (!$(element).next("span")[ 0 ]) {
                $("<span class='glyphicon glyphicon-ok form-control-feedback'></span>").insertAfter($(element));
            }
        },
        highlight: function (element, errorClass, validClass) {
            $(element).parents(".col-md-4").addClass("has-error").removeClass("has-success");
            $(element).next("span").addClass("glyphicon-remove").removeClass("glyphicon-ok");
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).parents(".col-md-4").addClass("has-success").removeClass("has-error");
            $(element).next("span").addClass("glyphicon-ok").removeClass("glyphicon-remove");
        }
    });


    $("#registrationForm").validate({
        rules: {
            title: "required",
            name: {
                required: true,
//                        alphabetsnspace: true

            },
            guardian_name: {
                required: true

            },
            mob: {
                required: true,
                phoneUS: true

            },
            dob: {
                required: true,
                DateFormat: true,
                minAge: 10
            },

            gender: "required",

            weight: {
                required: true,
                number: true,
            },
            feet_size: "required",
            blood_group: "required",
            age: "required",
            collage_name: {
                required: true

            },

            occupation: "required",
            address: "required",
            permanent_address: "required",
            nationality: "required",
            vip: "required",
            vip_remark: "required",
            medication: "required",
            allergies: "required",
            diet: "required",
            concern: "required",
            hear_about: "required",
            t_shirt: "required",

            /*username1: {
             required: true,
             minlength: 2
             },
             password1: {
             required: true,
             minlength: 5
             },
             confirm_password1: {
             required: true,
             minlength: 5,
             equalTo: "#password1"
             },
             
             agree1: "required"*/
        },
        messages: {
            title: "Please enter title",
            name: {
                required: "Please enter name",
//                        alphabetsnspace:"Only characters required",
            },
            guardian_name: {
                required: "Please enter guardian name",
//                        alphabetsnspace:"Only characters required",
            },
            mob: "Please enter contact number",
            alternate_no: "Please enter alternate number",
            dob: {
                required: "Please enter you date of birth.",
                minAge: "You must be at least 10 years old!"
            },
            gender: "Please select gender",
           
            weight: {
                required: "weight is required",
                number: "Please enter numeric value",
            },
            feet_size: "Please enter feet size",
            blood_group: "Please enter blood group",
            age: "Please enter age",
            collage_name: {
                required: "Please enter college name",
                alphabetsnspace: "Only characters required",
            },
            occupation: "Please enter occupation",
            email: {
                required: "Please enter a valid email address",
            },
            address: "Please enter address",
            permanent_address: "Please enter permanent address",
            nationality: "Please enter nationality",
            vip: "Please select vip status",
            vip_remark: "Please enter vip_remark",
            medication: "Please enter medication",
            allergies: "Please enter allergies",
            diet: "Please enter diet",
            concern: "Please enter concern",
            hear_about: "Please enter hear about",
            t_shirt: "Please select t-shirt size",
        },
        errorElement: "em",
        errorPlacement: function (error, element) {
            // Add the `help-block` class to the error element
            error.addClass("help-block");

            // Add `has-feedback` class to the parent div.form-group
            // in order to add icons to inputs
            element.parents(".col-md-4,.col-sm-4,.col-lg-4").addClass("has-feedback");

            if (element.prop("type") === "checkbox") {
                error.insertAfter(element.parent("label"));
            } else {
                error.insertAfter(element);
            }

            // Add the span element, if doesn't exists, and apply the icon classes to it.
            if (!element.next("span")[ 0 ]) {
                $("<span class='glyphicon glyphicon-remove form-control-feedback'></span>").insertAfter(element);
            }
        },
        success: function (label, element) {
            // Add the span element, if doesn't exists, and apply the icon classes to it.
            if (!$(element).next("span")[ 0 ]) {
                $("<span class='glyphicon glyphicon-ok form-control-feedback'></span>").insertAfter($(element));
            }
        },
        highlight: function (element, errorClass, validClass) {
            $(element).parents(".col-md-4,.col-sm-4,.col-lg-4").addClass("has-error").removeClass("has-success");
            $(element).next("span").addClass("glyphicon-remove").removeClass("glyphicon-ok");
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).parents(".col-md-4,.col-sm-4,.col-lg-4").addClass("has-success").removeClass("has-error");
            $(element).next("span").addClass("glyphicon-ok").removeClass("glyphicon-remove");
        }
    });

    $("#feeForm").validate({
        rules: {
            paid_amount: {
                required: true,
                number: true
            },
            pay_mode: "required",
        },
        messages: {
            paid_amount: "Please enter paid amount",
            pay_mode: "Please select pay mode",
        },
        errorElement: "em",
        errorPlacement: function (error, element) {
            // Add the `help-block` class to the error element
            error.addClass("help-block");

            // Add `has-feedback` class to the parent div.form-group
            // in order to add icons to inputs
            element.parents(".col-md-4,.col-sm-4,.col-lg-4,.col-md-12").addClass("has-feedback");

            if (element.prop("type") === "checkbox") {
                error.insertAfter(element.parent("label"));
            } else {
                error.insertAfter(element);
            }

            // Add the span element, if doesn't exists, and apply the icon classes to it.
            if (!element.next("span")[ 0 ]) {
                $("<span class='glyphicon glyphicon-remove form-control-feedback'></span>").insertAfter(element);
            }
        },
        success: function (label, element) {
            // Add the span element, if doesn't exists, and apply the icon classes to it.
            if (!$(element).next("span")[ 0 ]) {
                $("<span class='glyphicon glyphicon-ok form-control-feedback'></span>").insertAfter($(element));
            }
        },
        highlight: function (element, errorClass, validClass) {
            $(element).parents(".col-md-4,.col-sm-4,.col-lg-4,.col-md-12").addClass("has-error").removeClass("has-success");
            $(element).next("span").addClass("glyphicon-remove").removeClass("glyphicon-ok");
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).parents(".col-md-4,.col-sm-4,.col-lg-4,.col-md-12").addClass("has-success").removeClass("has-error");
            $(element).next("span").addClass("glyphicon-ok").removeClass("glyphicon-remove");
        }
    });


    $.validator.addMethod('notNone', function (value, element) {
        return (value > '0');
    }, 'Please select an option.');

    $("#academicForm").validate({
        rules: {
            'chequeNo[]': {
                required: true
            },
            remarks_wpdc: {
                required: true
            },
            chequeorcash_pdc_wpdc: {
                required: true
            },
            remarks_pdc: {
                required: true
            },
            chequeorcash_pdc_pdc: {
                required: true
            },
            'chequebank[]': {
                required: true
            },
            'chequeDate[]': {
                required: true
            },
            'chequeamt[]': {
                required: true
            },
            admission_date: {
                required: true,
            },
            paymentMode: {
                required: true,
            },
            lane_id: {
                required: true,
                notNone: true

            },
            aweapon: {
                required: true,
                notNone: true
            },
            pweapon: {
                required: true,
                notNone: true
            },
            check_time: "required",
            course: "required",
            time: "required",
            end_date: "required",

            status: "required",
            scholarship: "required",
            scholarship_amount: "required",
            scholarship_type: "required",
            scholarship_remarks: "required",
            additional_benefit: "required",
            additional_benefit_remarks: "required",
            course_fee: "required",
            apprefno: "required",
            nextpaydate: "required",
            'pdc_amount[]': "required",

            pname: {
                required: true

            },
            cert_serialno: "required",
            phone: {
                required: true

            },
            level1score: "required",
            'chequeorcash[]': "required",
            chequeorcash1: "required",
            chequeno: "required",
            transactionid: "required",
            bankbranch: "required",
            lane_allot: 'required',
            fee_amount: {
                required: true,
                min: 100
            },
            due_amount: {
                required: true,
                min: 100
            },
            fee_amount_pdc: {
                required: true,
                min: 100
            },
            due_amount_pdc: {
                required: true,
                min: 100
            },
            'pdc_amount_pdc[]': "required",
            'remarks[]': "required",
            'chequeorcash_pdc[]': "required",
            'remarks_pdc[]': "required",
            'chequeDate_pdc[]': "required"

        },

        messages: {
            fee_amount: {
                required: "amount to be paid is required",
                min: "Minimum 100 Rs required",
                max: "amount should not be grater than course fee",
            },
            pname: {
                required: "Participant name is required"

            },
            phone: {
                required: true,
                minlength: "Enter valid contact number",
                maxlength: "Enter valid contact number",
            }

        },
        errorElement: "em",
        errorPlacement: function (error, element) {
            // Add the `help-block` class to the error element
            error.addClass("help-block");

            // Add `has-feedback` class to the parent div.form-group
            // in order to add icons to inputs
            element.parents(".col-md-4,.col-sm-4,.col-lg-4,.col-md-12,col-lg-12").addClass("has-feedback");

            if (element.prop("type") === "checkbox") {
                error.insertAfter(element.parent("label"));
            } else {
                error.insertAfter(element);
            }

            // Add the span element, if doesn't exists, and apply the icon classes to it.
            if (!element.next("span")[ 0 ]) {
                $("<span class='glyphicon glyphicon-remove form-control-feedback'></span>").insertAfter(element);
            }
        },
        success: function (label, element) {
            // Add the span element, if doesn't exists, and apply the icon classes to it.
            if (!$(element).next("span")[ 0 ]) {
                $("<span class='glyphicon glyphicon-ok form-control-feedback'></span>").insertAfter($(element));
            }
        },
        highlight: function (element, errorClass, validClass) {
            $(element).parents(".col-sm-2,col-sm-3, .col-md-4,.col-sm-4,.col-lg-4,.col-md-12,.col-lg-12").addClass("has-error").removeClass("has-success");
            $(element).next("span").addClass("glyphicon-remove").removeClass("glyphicon-ok");
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).parents(".col-sm-2,col-sm-3,.col-md-4,.col-sm-4,.col-lg-4,.col-md-12,.col-lg-12").addClass("has-success").removeClass("has-error");
            $(element).next("span").addClass("glyphicon-ok").removeClass("glyphicon-remove");
        }
        
    });


    $("#editacademicForm").validate({
        rules: {
            admission_date: {
                required: true,
            },
            course: "required",
            time: "required",
            end_date: "required",
            weapon: "required",
            lane: "required",
        },
        errorElement: "em",
        errorPlacement: function (error, element) {
            // Add the `help-block` class to the error element
            error.addClass("help-block");

            // Add `has-feedback` class to the parent div.form-group
            // in order to add icons to inputs
            element.parents(".col-md-4,.col-sm-4,.col-lg-4,.col-md-12").addClass("has-feedback");

            if (element.prop("type") === "checkbox") {
                error.insertAfter(element.parent("label"));
            } else {
                error.insertAfter(element);
            }

            // Add the span element, if doesn't exists, and apply the icon classes to it.
            if (!element.next("span")[ 0 ]) {
                $("<span class='glyphicon glyphicon-remove form-control-feedback'></span>").insertAfter(element);
            }
        },
        success: function (label, element) {
            // Add the span element, if doesn't exists, and apply the icon classes to it.
            if (!$(element).next("span")[ 0 ]) {
                $("<span class='glyphicon glyphicon-ok form-control-feedback'></span>").insertAfter($(element));
            }
        },
        highlight: function (element, errorClass, validClass) {
            $(element).parents(".col-md-4,.col-sm-4,.col-lg-4,.col-md-12").addClass("has-error").removeClass("has-success");
            $(element).next("span").addClass("glyphicon-remove").removeClass("glyphicon-ok");
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).parents(".col-md-4,.col-sm-4,.col-lg-4,.col-md-12").addClass("has-success").removeClass("has-error");
            $(element).next("span").addClass("glyphicon-ok").removeClass("glyphicon-remove");
        }
    });

    $("#updateRegistraionForm").validate({
        rules: {
            title: "required",
            name: {
                required: true,
//                        alphabetsnspace: true,
            },
            gender: "required",
            dob: {
                required: true,
                DateFormat: true,
                minAge: 10
            },
            guardian_name: {
                required: true,
//                        alphabetsnspace: true,
            },
            mob: {
                required: true,
                phoneUS: true

            },

            age: "required",

        },
        messages: {
            dob: {
                required: "Please enter you date of birth.",
                minAge: "You must be at least 10 years old!"
            }
        },
        errorElement: "em",
        errorPlacement: function (error, element) {
            // Add the `help-block` class to the error element
            error.addClass("help-block");

            // Add `has-feedback` class to the parent div.form-group
            // in order to add icons to inputs
            element.parents(".col-md-4,.col-sm-4,.col-lg-4,.col-md-12").addClass("has-feedback");

            if (element.prop("type") === "checkbox") {
                error.insertAfter(element.parent("label"));
            } else {
                error.insertAfter(element);
            }

            // Add the span element, if doesn't exists, and apply the icon classes to it.
            if (!element.next("span")[ 0 ]) {
                $("<span class='glyphicon glyphicon-remove form-control-feedback'></span>").insertAfter(element);
            }
        },
        success: function (label, element) {
            // Add the span element, if doesn't exists, and apply the icon classes to it.
            if (!$(element).next("span")[ 0 ]) {
                $("<span class='glyphicon glyphicon-ok form-control-feedback'></span>").insertAfter($(element));
            }
        },
        highlight: function (element, errorClass, validClass) {
            $(element).parents(".col-md-4,.col-sm-4,.col-lg-4,.col-md-12").addClass("has-error").removeClass("has-success");
            $(element).next("span").addClass("glyphicon-remove").removeClass("glyphicon-ok");
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).parents(".col-md-4,.col-sm-4,.col-lg-4,.col-md-12").addClass("has-success").removeClass("has-error");
            $(element).next("span").addClass("glyphicon-ok").removeClass("glyphicon-remove");
        }
    });

    $("#remarkForm").validate({
        rules: {
            walkin_name: {
                required: true,
//                        alphabetsnspace: true,
            },
            remarks: "required",
            reminder_date: "required",
        },
        messages: {
            walkin_name: "name is required",
            remarks: "remarks is required",
            reminder_date: "reminder date is required",
        },
        errorElement: "em",
        errorPlacement: function (error, element) {
            // Add the `help-block` class to the error element
            error.addClass("help-block");

            // Add `has-feedback` class to the parent div.form-group
            // in order to add icons to inputs
            element.parents(".col-md-4,.col-sm-4,.col-lg-4,.col-md-12").addClass("has-feedback");

            if (element.prop("type") === "checkbox") {
                error.insertAfter(element.parent("label"));
            } else {
                error.insertAfter(element);
            }

            // Add the span element, if doesn't exists, and apply the icon classes to it.
            if (!element.next("span")[ 0 ]) {
                $("<span class='glyphicon glyphicon-remove form-control-feedback'></span>").insertAfter(element);
            }
        },
        success: function (label, element) {
            // Add the span element, if doesn't exists, and apply the icon classes to it.
            if (!$(element).next("span")[ 0 ]) {
                $("<span class='glyphicon glyphicon-ok form-control-feedback'></span>").insertAfter($(element));
            }
        },
        highlight: function (element, errorClass, validClass) {
            $(element).parents(".col-md-4,.col-sm-4,.col-lg-4,.col-md-12").addClass("has-error").removeClass("has-success");
            $(element).next("span").addClass("glyphicon-remove").removeClass("glyphicon-ok");
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).parents(".col-md-4,.col-sm-4,.col-lg-4,.col-md-12").addClass("has-success").removeClass("has-error");
            $(element).next("span").addClass("glyphicon-ok").removeClass("glyphicon-remove");
        }
    });

    $("#tispForm").validate({
        rules: {
            student_name: {
                required: true

            },
            height: "required",
            width: "required",
            reactionTime: "required",
            balanceRight: "required",
            balanceLeft: "required",
            plankHold: "required",
            groupRing: "required",
            flexibility: "required",
            bmiScore: "required",
            finger_dexterity_l: "required",
            finger_dexterity_r: "required",
            groupRing: "required",
        },
        messages: {
            student_name: "name is required",
            height: "height is required",
            width: "weight is required",
        },
        errorElement: "em",
        errorPlacement: function (error, element) {
            // Add the `help-block` class to the error element
            error.addClass("help-block");

            // Add `has-feedback` class to the parent div.form-group
            // in order to add icons to inputs
            element.parents(".col-md-4,.col-sm-4,.col-lg-4,.col-md-12,.col-md-6").addClass("has-feedback");

            if (element.prop("type") === "checkbox") {
                error.insertAfter(element.parent("label"));
            } else {
                error.insertAfter(element);
            }

            // Add the span element, if doesn't exists, and apply the icon classes to it.
            if (!element.next("span")[ 0 ]) {
                $("<span class='glyphicon glyphicon-remove form-control-feedback'></span>").insertAfter(element);
            }
        },
        success: function (label, element) {
            // Add the span element, if doesn't exists, and apply the icon classes to it.
            if (!$(element).next("span")[ 0 ]) {
                $("<span class='glyphicon glyphicon-ok form-control-feedback'></span>").insertAfter($(element));
            }
        },
        highlight: function (element, errorClass, validClass) {
            $(element).parents(".col-md-4,.col-sm-4,.col-lg-4,.col-md-12,.col-md-6").addClass("has-error").removeClass("has-success");
            $(element).next("span").addClass("glyphicon-remove").removeClass("glyphicon-ok");
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).parents(".col-md-4,.col-sm-4,.col-lg-4,.col-md-12,.col-md-6").addClass("has-success").removeClass("has-error");
            $(element).next("span").addClass("glyphicon-ok").removeClass("glyphicon-remove");
        }
    });

    $("#update_lane").validate({
        rules: {

            admission_date: "required",
            course: {
                required: true,
                min: 1
            },
            time: {
                required: true,
                min: 1
            },
            aweapon: {
                required: true,
                min: 1
            },
            pweapon: {
                required: true,
                min: 1
            },
            lane_id: {
                required: true,
                min: 1
            },
            end_date: "required",

        },
        messages: {

        },
        errorElement: "em",
        errorPlacement: function (error, element) {
            // Add the `help-block` class to the error element
            error.addClass("help-block");

            // Add `has-feedback` class to the parent div.form-group
            // in order to add icons to inputs
            element.parents(".col-md-4,.col-sm-4,.col-lg-4,.col-md-12,.col-md-6").addClass("has-feedback");

            if (element.prop("type") === "checkbox") {
                error.insertAfter(element.parent("label"));
            } else {
                error.insertAfter(element);
            }

            // Add the span element, if doesn't exists, and apply the icon classes to it.
            if (!element.next("span")[ 0 ]) {
                $("<span class='glyphicon glyphicon-remove form-control-feedback'></span>").insertAfter(element);
            }
        },
        success: function (label, element) {
            // Add the span element, if doesn't exists, and apply the icon classes to it.
            if (!$(element).next("span")[ 0 ]) {
                $("<span class='glyphicon glyphicon-ok form-control-feedback'></span>").insertAfter($(element));
            }
        },
        highlight: function (element, errorClass, validClass) {
            $(element).parents(".col-md-4,.col-sm-4,.col-lg-4,.col-md-12,.col-md-6").addClass("has-error").removeClass("has-success");
            $(element).next("span").addClass("glyphicon-remove").removeClass("glyphicon-ok");
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).parents(".col-md-4,.col-sm-4,.col-lg-4,.col-md-12,.col-md-6").addClass("has-success").removeClass("has-error");
            $(element).next("span").addClass("glyphicon-ok").removeClass("glyphicon-remove");
        }
    });
    
 

});

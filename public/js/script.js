$(function() {
                $( "#exchageForm" ).submit(function( event ) {
                    event.preventDefault();
                    
                    if(validateForm() === false){
                        return;
                    }

                    getExchangeInfo();
                });
            });
            
                
            function validateForm() {
                var from = $('#exchageForm .from').val();
                var to = $('#exchageForm .to').val();
                var amount = $('#exchageForm .amount').val();

                // validate amount (not empty & numbric)
                if(amount === "") {
                    $("#amount_error").html("amount should not be empty!");
                    return false;
                }
                if(!amount.match(/^\d+$/)) {
                    $("#amount_error").html("amount must be number!");
                    return false;
                }

                // validate fromInput (be selected!)
                // validate toInput (be selected!)


                // if all inputs are valid delete errors and return true;
                deleteValidateErrors();
                return true;
            }

            /**
             * delete errors of validation
             *
             */
            function deleteValidateErrors() {
                $("#amount_error").html("");
            }

            /**
             * get exchange data with ajax call
             * 
             */
            function getExchangeInfo() {
                
                // first retrieve data from form
                var from = $('#exchageForm .from').val();
                var to = $('#exchageForm .to').val();
                var amount = $('#exchageForm .amount').val();

                // request information with ajax call
                $.ajax({
                    type: 'POST',
                    url: "index/getexchangeinfo",
                    data: {
                        from: from,
                        to: to,
                        amount: amount,
                        csrf: $('#csrf').val()}
                })
                .done(function (data) {

                    console.debug(data);
                    
                    // update html view with new data that came from server
                    updateViewWithExchangeInfo(data);
                });
            }
            
            function updateViewWithExchangeInfo(data) {
                
                if(data.status === 'success') {
                    $("#exchangeResult").html(data.toAmount);
                    
                    // update csrf token
                    updateCsrfToken(data.token.hash);
                }
                else {
                    $("#amount_error").html(data.errMsg.amount);
                    $("#from_error").html(data.errMsg.from);
                    $("#to_error").html(data.errMsg.to);
                   
                    // update csrf token
                    updateCsrfToken(data.token.hash);
                }
            }
            
            /**
             * update csrf token input value
             * 
             */
            function updateCsrfToken(hash) {
                $('#csrf').val(hash);
            }
            
            /**
             * 
             * clear #exchangeResult 
             */
            function clearResult() {
                $("#exchangeResult").html("");
            }
            
            /**
             * swap currencies of select box
             */
            function swapCurrencies() {
                var from = $('#exchageForm .from').val();
                var to = $('#exchageForm .to').val();
                
                // swap
                $('#exchageForm .from').val(to);
                $('#exchageForm .to').val(from);
                
                // clear result
                clearResult();
            }

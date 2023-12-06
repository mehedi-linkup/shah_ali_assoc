const paymentInvoice = Vue.component('payment-invoice', {
    template: `
        <div>
            <div class="row">
                <div class="col-xs-12">
                    <a href="" v-on:click.prevent="print"><i class="fa fa-print"></i> Print</a>
                </div>
            </div>
            
            <div id="invoiceContent">
                <div class="row">
                    <div class="col-xs-12 text-center">
                        <div _h098asdh>
                            <span style="font-size:20px;font-weight:bold">সেন্ট্রাল এসি বিল</span><br>
                            <span> {{ startDate }} হইতে {{ endDate }}</span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-7">
                        <strong>মিটার নংঃ  </strong> {{ cart[0]?.meter_no }}<br>
                        <strong>নামঃ </strong> {{ cart[0]?.Store_Name }}<br>
                        <strong>দোকান নংঃ </strong> {{ cart[0]?.Owner_Name }}<br>
                        <strong>আয়তনঃ </strong> {{ cart[0]?.square_feet }}<br>
                    </div>
                    <div class="col-xs-5 text-right">
                        <strong>বিল তৈরির তারিখঃ </strong> {{ (cart[0]?.process_date) }}<br>
                        <strong>বিল প্রদানের তারিখঃ </strong>{{ (payment.payment_date) }}<br>
                        <strong>বিচ্ছিন্ন করণের তারিখঃ </strong> {{ addMonthsToDate(cart[0]?.process_date, 2) }}
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        <div _d9283dsc style="border:transparent"></div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        <table _a584de>
                            <thead>
                                <tr style="border-bottom: 1px solid !important">
                                    <td>বিল আই.ডি</td>
                                    <td>খাতের নাম</td>
                                    <td>বিলের পরিমাণ</td>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(product, sl) in cart">
                                    <td>{{ payment.invoice }}</td>
                                    <td>এ সি চার্জ</td>
                                    <td>{{ product.ac_bill }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-xs-6"></div>
                    <div class="col-xs-6">
                        <table _t92sadbc2>
                            <tr>
                                <td><strong>মোট বিল</strong></td>
                                <td style="text-align:right">{{ cart[0]?.ac_bill }}</td>
                            </tr>
                            <tr>
                                <td><strong>পূর্বের বকেয়া</strong></td>
                                <td style="text-align:right">{{ cart[0]?.previous_due }}</td>
                            </tr>
                            <tr>
                                <td><strong>সর্বমোট :</strong></td>
                                <td style="text-align:right">{{ +cart[0]?.ac_bill + +cart[0]?.previous_due }}</td>
                            </tr>
                            <tr>
                                <td><strong>বিলম্ব ফি:</strong></td>
                                <td style="text-align:right">{{ cart[0]?.late_fee }}</td>
                            </tr>
                            <tr><td colspan="2" style="border-bottom: 1px solid #ccc"></td></tr>
                            <tr>
                                <td><strong>বিলম্ব ফি সহ সর্বমোট :</strong></td>
                                <td style="text-align:right">{{ +cart[0]?.ac_bill + +cart[0]?.previous_due + +cart[0]?.late_fee }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    `,
    props: ['payment_id'],
    data(){
        return {
            payment:{
                invoice: null,
                month_id: null,
                month_name: null,
                payment_date: null,
                saved_at: null,
                saved_by: null,
                total_payment: null,
                saved_by: null
            },
            cart: [],
            style: null,
            companyProfile: null,
            currentBranch: null,
            startDate: '',
            endDate: '',
        }
    },
    filters: {
        formatDateTime(dt, format) {
            return dt == '' || dt == null ? '' : moment(dt).format(format);
        }
    },
    created(){
        this.setStyle();
        this.getpayment();
        this.getCurrentBranch();
    },
   
    methods:{
        getpayment(){
            axios.post('/get_utility_payment', {id: this.payment_id}).then(res=>{
                this.payment = res.data.payments[0];
                this.cart = res.data.paymentDetails;
                this.calculateDates();
            })
        },
        getCurrentBranch() {
            axios.get('/get_current_branch').then(res => {
                this.currentBranch = res.data;
                console.log(this.currentBranch);
            })
        },
        setStyle(){
            this.style = document.createElement('style');
            this.style.innerHTML = `
                div[_h098asdh]{
                    /*background-color:#e0e0e0;*/
                    /* font-weight: bold; */
                    font-size:15px;
                    margin-bottom:15px;
                    padding: 5px;
                }
                div[_d9283dsc]{
                    padding-bottom:25px;
                    border-bottom: 1px solid #ccc;
                    margin-bottom: 15px;
                }
                table[_a584de]{
                    width: 100%;
                    text-align:center;
                }
                table[_a584de] thead{
                    font-weight:bold;
                }
                table[_a584de] td{
                    padding: 3px;
                 
                }
                
                table[_t92sadbc2]{
                    width: 100%;
                }
                table[_t92sadbc2] td{
                    padding: 2px;
                }
            `;
            document.head.appendChild(this.style);
        },
        calculateDates() {
            const [monthName, year] = this.payment.month_name.split(' ');
            const monthIndex = new Date(`${monthName} 1, ${year}`).getMonth();
            const startDate = new Date(year, monthIndex, 1);
            const endDate = new Date(year, monthIndex + 1, 0);
            
            const startDay = this.padWithBengaliNumerals(startDate.getDate());
            const startMonth = this.padWithBengaliNumerals(startDate.getMonth() + 1); // Months are zero-based
            const startYear = this.padWithBengaliNumerals(startDate.getFullYear());

            const endDay = this.padWithBengaliNumerals(endDate.getDate());
            const endMonth = this.padWithBengaliNumerals(endDate.getMonth() + 1); // Months are zero-based
            const endYear = this.padWithBengaliNumerals(endDate.getFullYear());

            this.startDate = `${startDay}/${startMonth}/${startYear}`;
            this.endDate = `${endDay}/${endMonth}/${endYear}`;
            // this.startDate = startDate.toISOString().split('T')[0];
            // this.endDate = endDate.toISOString().split('T')[0];
        },
        addMonthsToDate(inputDate, monthsToAdd) {
            const date = new Date(inputDate);
            date.setMonth(date.getMonth() + monthsToAdd);
            return date;
        },
        // padWithBengalidate(date) {
        //     const date = new Date(date).getMonth();
        //     const startDate = new Date(year, monthIndex, 1);
        //     const endDate = new Date(year, monthIndex + 1, 0);
        // },
        padWithBengaliNumerals(number) {
            const bengaliNumerals = ['০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯'];
            return String(number).split('').map(digit => bengaliNumerals[parseInt(digit)]).join('');
          },
        convertNumberToWords(amountToWord) {
            var words = new Array();
            words[0] = '';
            words[1] = 'One';
            words[2] = 'Two';
            words[3] = 'Three';
            words[4] = 'Four';
            words[5] = 'Five';
            words[6] = 'Six';
            words[7] = 'Seven';
            words[8] = 'Eight';
            words[9] = 'Nine';
            words[10] = 'Ten';
            words[11] = 'Eleven';
            words[12] = 'Twelve';
            words[13] = 'Thirteen';
            words[14] = 'Fourteen';
            words[15] = 'Fifteen';
            words[16] = 'Sixteen';
            words[17] = 'Seventeen';
            words[18] = 'Eighteen';
            words[19] = 'Nineteen';
            words[20] = 'Twenty';
            words[30] = 'Thirty';
            words[40] = 'Forty';
            words[50] = 'Fifty';
            words[60] = 'Sixty';
            words[70] = 'Seventy';
            words[80] = 'Eighty';
            words[90] = 'Ninety';
            amount = amountToWord == null ? '0.00' : amountToWord.toString();
            var atemp = amount.split(".");
            var number = atemp[0].split(",").join("");
            var n_length = number.length;
            var words_string = "";
            if (n_length <= 9) {
                var n_array = new Array(0, 0, 0, 0, 0, 0, 0, 0, 0);
                var received_n_array = new Array();
                for (var i = 0; i < n_length; i++) {
                    received_n_array[i] = number.substr(i, 1);
                }
                for (var i = 9 - n_length, j = 0; i < 9; i++, j++) {
                    n_array[i] = received_n_array[j];
                }
                for (var i = 0, j = 1; i < 9; i++, j++) {
                    if (i == 0 || i == 2 || i == 4 || i == 7) {
                        if (n_array[i] == 1) {
                            n_array[j] = 10 + parseInt(n_array[j]);
                            n_array[i] = 0;
                        }
                    }
                }
                value = "";
                for (var i = 0; i < 9; i++) {
                    if (i == 0 || i == 2 || i == 4 || i == 7) {
                        value = n_array[i] * 10;
                    } else {
                        value = n_array[i];
                    }
                    if (value != 0) {
                        words_string += words[value] + " ";
                    }
                    if ((i == 1 && value != 0) || (i == 0 && value != 0 && n_array[i + 1] == 0)) {
                        words_string += "Crores ";
                    }
                    if ((i == 3 && value != 0) || (i == 2 && value != 0 && n_array[i + 1] == 0)) {
                        words_string += "Lakhs ";
                    }
                    if ((i == 5 && value != 0) || (i == 4 && value != 0 && n_array[i + 1] == 0)) {
                        words_string += "Thousand ";
                    }
                    if (i == 6 && value != 0 && (n_array[i + 1] != 0 && n_array[i + 2] != 0)) {
                        words_string += "Hundred and ";
                    } else if (i == 6 && value != 0) {
                        words_string += "Hundred ";
                    }
                }
                words_string = words_string.split("  ").join(" ");
            }
            return words_string + ' only';
        },
        async print(){
            let invoiceContent = document.querySelector('#invoiceContent').innerHTML;
            let printWindow = window.open('', 'PRINT', `width=${screen.width}, height=${screen.height}, left=0, top=0`);
            if (this.currentBranch.print_type == '3') {
                printWindow.document.write(`
                    <html>
                        <head>
                            <title>Invoice</title>
                            <link rel="stylesheet" href="/assets/css/bootstrap.min.css">
                            <style>
                                body, table{
                                    font-size:11px;
                                }
                            </style>
                        </head>
                        <body>
                            <div style="text-align:center;">
                                <img src="/uploads/company_profile_thum/${this.currentBranch.Company_Logo_org}" alt="Logo" style="height:80px;margin:0px;" /><br>
                                <strong style="font-size:18px;">${this.currentBranch.Company_Name}</strong><br>
                                <strong style="font-size:18px;">${this.currentBranch.Company_Name_Bangla}</strong><br><br>
                                <p style="white-space:pre-line;">${this.currentBranch.Repot_Heading}</p>
                            </div>
                            ${invoiceContent}
                        </body>
                    </html>
                `);
            } else if (this.currentBranch.print_type == '2') {
                printWindow.document.write(`
                    <!DOCTYPE html>
                    <html lang="en">
                    <head>
                        <meta charset="UTF-8">
                        <meta name="viewport" content="width=device-width, initial-scale=1.0">
                        <meta http-equiv="X-UA-Compatible" content="ie=edge">
                        <title>Invoice</title>
                        <link rel="stylesheet" href="/assets/css/bootstrap.min.css">
                        <style>
                            html, body{
                                width:500px!important;
                            }
                            body, table{
                                font-size: 13px;
                            }
                        </style>
                    </head>
                    <body>
                        <div class="row">
                            <div class="col-xs-2" style="padding-top:20px;"><img src="/uploads/company_profile_thum/${this.currentBranch.Company_Logo_org}" alt="Logo" style="height:80px;" /></div>
                            <div class="col-xs-10" style="padding-top:20px;">
                                <strong style="font-size:18px;">${this.currentBranch.Company_Name}</strong><br>
                                <strong style="font-size:18px;">${this.currentBranch.Company_Name_Bangla}</strong><br><br>
                                <p style="white-space:pre-line;">${this.currentBranch.Repot_Heading}</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12">
                                <div style="border-bottom: 2px solid #454545;margin-top:7px;margin-bottom:7px;"></div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12">
                                ${invoiceContent}
                            </div>
                        </div>
                    </body>
                    </html>
				`);
            } else {
				printWindow.document.write(`
                    <!DOCTYPE html>
                    <html lang="en">
                    <head>
                        <meta charset="UTF-8">
                        <meta name="viewport" content="width=device-width, initial-scale=1.0">
                        <meta http-equiv="X-UA-Compatible" content="ie=edge">
                        <title>Invoice</title>
                        <link rel="stylesheet" href="/assets/css/bootstrap.min.css">
                        <style>
                            body, table{
                                font-size: 13px;
                            }
                        </style>
                    </head>
                    <body>
                        <div class="container">
                            <table style="width:100%;">
                                <thead>
                                    <tr>
                                        <td>
                                            <div class="row">
                                                <div class="col-xs-2" style="padding-top:20px;"><img src="/uploads/company_profile_thum/${this.currentBranch.Company_Logo_org}" alt="Logo" style="height:80px;" /></div>
                                                <div class="col-xs-10" style="padding-top:20px;">
                                                    <strong style="font-size:18px;">${this.currentBranch.Company_Name}</strong><br>
                                                    <strong style="font-size:18px;">${this.currentBranch.Company_Name_Bangla}</strong><br><br>
                                                    <p style="white-space:pre-line;">${this.currentBranch.Repot_Heading}</p>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-xs-12">
                                                    <div style="border-bottom: 4px double #454545;margin-top:7px;margin-bottom:7px;"></div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <div class="row">
                                                <div class="col-xs-12">
                                                    ${invoiceContent}
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td>
                                            <div style="width:100%;height:50px;">&nbsp;</div>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                            <div class="row" style="margin-bottom:5px;padding-bottom:6px;">
                                <div class="col-xs-4">
                                    <span style="text-decoration:overline;">ইলেকট্রিশিয়ান</span><br><br>
                                </div>
                                <div class="col-xs-4 text-center">
                                    <span style="text-decoration:overline;">সেক্রেটারি</span><br><br>
                                </div>
                                <div class="col-xs-4 text-right">
                                    <span style="text-decoration:overline;">আদায়কারী</span>
                                </div>
                            </div>
                            <div style="position:fixed;left:0;bottom:15px;width:100%;">
                                <div class="row" style="font-size:12px;">
                                   <div class="col-xs-12 text-center">(বিলের কপি হারানো গেলে ২০/- জরিমানা দিয়ে নতুন কপি সংগ্রহ করতে হবে)</div>
                                </div>
                            </div>
                        </div>
                        
                    </body>
                    </html>
				`);
            }
            let invoiceStyle = printWindow.document.createElement('style');
            invoiceStyle.innerHTML = this.style.innerHTML;
            printWindow.document.head.appendChild(invoiceStyle);
            printWindow.moveTo(0, 0);
            
            printWindow.focus();
            await new Promise(resolve => setTimeout(resolve, 1000));
            printWindow.print();
            printWindow.close();
        }
    }
})
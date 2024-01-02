const billInvoice = Vue.component('billInvoice', {
    template: `
        <div>
            <div class="row">
                <div class="col-xs-12">
                    <a href="" v-on:click.prevent="print"><i class="fa fa-print"></i>Print</a>
                </div>
            </div>
            
            <div id="invoiceContent">
                <div class="row">
                    <div class="col-xs-12 text-center">
                        <div _h098asdh>
                            <span style="font-size:20px;font-weight:bold">মাসিক ভাড়া</span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-7">
                        <strong>দোকান / স্পেস নংঃ </strong> {{ convertToBanglaNumber(bill?.Store_No) }} {{ bill?.Floor_Name }}<br>
                        <strong>সদস্য / আইডি নংঃ </strong> {{ bill?.Owner_Code }}<br>
                        <strong>নামঃ </strong> {{ bill?.Owner_Name }}<br>
                    </div>
                    <div class="col-xs-5 text-right">
                        <strong>মাসঃ </strong> {{ bill.month_name }}<br>
                        <strong>তারিখঃ </strong> {{ startDate }} হইতে {{ endDate }} পর্যন্ত <br>
                        <strong>আয়তন</strong> {{ convertToBanglaNumber(bill.square_feet) }}<br><br>
                      
                        <strong>বিচ্ছিন্ন করণের তারিখঃ </strong> {{ dateFormat(bill?.last_date) }}
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        <div _d9283dsc style="border-color:transparent"></div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        <table _a584de>
                            <thead>
                                <tr>
                                    <td style="text-align:left">নং </td>
                                    <td style="text-align:left">খাত নং</td>
                                    <td style="text-align:left">খাতের নাম </td>
                                    <td style="text-align:right">টাকা </td>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-if="bill.savings_deposit > 0">
                                    <td style="text-align:left">১</td>
                                    <td style="text-align:left">{{ convertToBanglaNumber(101001) }}</td>
                                    <td style="text-align:left">সঞ্চয় আমানত</td>
                                    <td style="text-align:right">{{ convertToBanglaNumber(bill?.savings_deposit) }}</td>
                                </tr>
                                <tr v-if="bill.membership_fee > 0">
                                    <td style="text-align:left">২</td>
                                    <td style="text-align:left">{{ convertToBanglaNumber(301001) }}</td>
                                    <td style="text-align:left">সদস্য চাঁদা</td>
                                    <td style="text-align:right">{{ convertToBanglaNumber(bill?.membership_fee) }}</td>
                                </tr>
                                <tr>
                                    <td style="text-align:left">{{ bill.membership_fee == 0? '১':'৩'}}</td>
                                    <td style="text-align:left">{{ convertToBanglaNumber(301005) }}</td>
                                    <td style="text-align:left">দোকান ভাড়া</td>
                                    <td style="text-align:right">{{ convertToBanglaNumber(bill?.shop_rent) }}</td>
                                </tr>
                                <tr>
                                    <td style="text-align:left">{{ bill.membership_fee == 0? '২':'৪'}}</td>
                                    <td style="text-align:left">{{ convertToBanglaNumber(301006) }}</td>
                                    <td style="text-align:left">কর সারচার্জ</td>
                                    <td style="text-align:right">{{ convertToBanglaNumber(bill?.tax_surcharge) }}</td>
                                </tr>
                                <tr>
                                    <td style="text-align:left">{{ bill.membership_fee == 0? '৩':'৫'}}</td>
                                    <td style="text-align:left">{{ convertToBanglaNumber(301007) }}</td>
                                    <td style="text-align:left">সার্ভিস চার্জ</td>
                                    <td style="text-align:right">{{ convertToBanglaNumber(bill?.tax_surcharge) }}</td>
                                </tr>
                                <tr>
                                    <td colspan="3" align="right">মোট বিলঃ</td>
                                    <td style="text-align:right">{{ convertToBanglaNumber(bill?.net_payable) }} </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-12"><hr style="margin-top:10px;border-color:transparent;margin-bottom:10px;"></div>
                    <div class="col-xs-6"></div>
                    <div class="col-xs-6">
                        <table _t92sadbc2>
                            <tr>
                                <td><strong>সর্বমোট:</strong></td>
                                <td style="text-align:right">{{ convertToBanglaNumber(bill?.net_payable) }}</td>
                            </tr>
                            <tr>
                                <td><strong>বিলম্ব ফি:</strong></td>
                                <td style="text-align:right">{{ convertToBanglaNumber(0) }}</td>
                            </tr>
                            <tr><td colspan="2" style="border-bottom: 1px solid #ccc"></td></tr>
                            <tr>
                                <td><strong>বিলম্ব ফি সহ সর্বমোট:</strong></td>
                                <td style="text-align:right">{{ convertToBanglaNumber(parseFloat( +bill?.net_payable + +0).toFixed(2) ) }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    `,
    props: ['bill_id'],
    data(){
        return {
            bill:{
                invoice: null,
                month_id: null,
                month_name: null,
                last_date: null,
                savings_deposit: null,
                service_charge: null,
                shop_rent: null,
                tax_surcharge: null,
                Store_Name: null,
                Owner_Name: null,
                Floor_Name: null,
                net_payable: null,

            },
            style: null,
            companyProfile: null,
            utilityRate: null,
            currentBranch: null,
            startDate: "",
            endDate: ""
        }
    },
    filters: {
        formatDateTime(dt, format) {
            return dt == '' || dt == null ? '' : moment(dt).format(format);
        }
    },
    created(){
        this.setStyle();
        this.getbill();
        this.getCurrentBranch();
        this.getUtilityRate();
    },
    methods:{
        getbill(){
            axios.post('/get_zamindari_bill_details', {billDetailId: this.bill_id}).then(res=>{
                this.bill = res.data[0];
                // this.cart = res.data.billDetails;
                console.log(this.bill);
                this.calculateDates();
            })
        },
        getCurrentBranch() {
            axios.get('/get_current_branch').then(res => {
                this.currentBranch = res.data;
            })
        },

        getUtilityRate() {
            axios.get('/get_utility_rate').then(res => {
                this.utilityRate = res.data;
            })
        },
        calculateDates() {
            const [monthName, year] = this.bill.month_name.split(' ');
            const monthIndex = new Date(`${monthName} 1, ${year}`).getMonth();
            const startDate = new Date(year, monthIndex, 1);
            const endDate = new Date(year, monthIndex + 1, 0);

            const formattedSDate = startDate.toLocaleDateString('en-US', {
                year: 'numeric',
                month: '2-digit',
                day: '2-digit',
              });
            const formattedEDate = endDate.toLocaleDateString('en-US', {
                year: 'numeric',
                month: '2-digit',
                day: '2-digit',
              });

            const [smonth, sday, syear] = formattedSDate.split('/');
            const formattedSDateDDMMYYYY = `${sday}/${smonth}/${syear}`;
            const [emonth, eday, eyear] = formattedEDate.split('/');
            const formattedEDateDDMMYYYY = `${eday}/${emonth}/${eyear}`;

            this.startDate  = this.convertToBengaliNumerals(formattedSDateDDMMYYYY);
            this.endDate = this.convertToBengaliNumerals(formattedEDateDDMMYYYY);
            console.log(this.startDate);
        },
        dateFormat(inputDate) {
            let date = new Date(inputDate);
            const formattedDate = date.toLocaleDateString('en-US', {
                year: 'numeric',
                month: '2-digit',
                day: '2-digit',
              });

            const [month, day, year] = formattedDate.split('/');
            const formattedDateDDMMYYYY = `${day}/${month}/${year}`;
            const convertedDate = this.convertToBengaliNumerals(formattedDateDDMMYYYY);

            return convertedDate;
        },
        addMonthsToDate(inputDate, monthsToAdd) {
            let date = new Date(inputDate);
            date.setMonth(date.getMonth() + monthsToAdd);
            const formattedDate = date.toLocaleDateString('en-US', {
                year: 'numeric',
                month: '2-digit',
                day: '2-digit',
              });
              
            const [month, day, year] = formattedDate.split('/');
            const formattedDateDDMMYYYY = `${day}/${month}/${year}`;
            const convertedDate = this.convertToBengaliNumerals(formattedDateDDMMYYYY);
            return convertedDate;
        },
        convertToBengaliNumerals(dateString) {
            const bengaliNumerals = ['০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯'];
          
            const convertedString = dateString.replace(/\d/g, (digit) => bengaliNumerals[digit]);
          
            return convertedString;
        },
        convertToBanglaNumber(number) {
            const bengaliNumerals = ['০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯'];
            const [integerPart, decimalPart] = String(number).split('.');
            const convertedIntegerPart = integerPart.split('').map(digit => bengaliNumerals[parseInt(digit)]).join('');
            const convertedDecimalPart = decimalPart ? `.${decimalPart.split('').map(digit => bengaliNumerals[parseInt(digit)]).join('')}` : '';
            const convertedNumber = `${convertedIntegerPart}${convertedDecimalPart}`;
            return convertedNumber;
        }, 
        setStyle(){
            this.style = document.createElement('style');
            this.style.innerHTML = `
                div[_h098asdh]{
                    /*background-color:#e0e0e0;*/
                    // font-weight: bold;
                    font-size:15px;
                    margin-bottom:15px;
                    padding: 5px;
                    // border-top: 1px dotted #454545;
                    // border-bottom: 1px dotted #454545;
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
                    border: 1px solid #ccc;
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
                                    <span style="text-decoration:overline;">প্রস্তুতকারী </span><br><br>
                                </div>
                                <div class="col-xs-4 text-center">
                                    <span style="text-decoration:overline;"></span><br><br>
                                </div>
                                <div class="col-xs-4 text-right">
                                    <span style="text-decoration:overline;">আদায়কারী</span>
                                </div>
                            </div>
                           
                        </div>
                        
                    </body>
                    </html>
				`);
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
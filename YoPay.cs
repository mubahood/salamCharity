using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using System.Net;
using System.IO;
using System.Xml;

namespace yopaymentapi
{
    class Program
    {
        public static object OutputTextBlock { get; private set; }

        private static Random random = new Random();

        static void Main(string[] args)
        {
            string url = "https://paymentsapi1.yo.co.ug/ybs/task.php";
            string api_username = "yoapiusername";
            string api_password = "yoapipassword";

            Console.WriteLine("Please select the operation to do next");
            Console.WriteLine("1. Request statement");
            Console.WriteLine("2. Send Bulk Payments");

            String s = Console.ReadLine();

            if (s == "1")
            {
                //Request for 
                Console.WriteLine("Requesting for Statement from Yo! Payments server");
                Console.WriteLine("---------------------------------------------------");

                string xml_data = getMiniStatementString(api_username, api_password);
                Console.WriteLine("Sent data");
                Console.WriteLine(xml_data);
                Console.WriteLine("---------------------------------------------------");

                string return_xml_string = makeRequest(xml_data, url);

                //returned data
                Console.WriteLine("Returned XML");
                Console.WriteLine("---------------------------------------------------");
                Console.WriteLine(return_xml_string);
                Console.WriteLine("--------------------------------------------------\n\n");
                Console.WriteLine(return_xml_string);

            } else if (s == "2") {

                List<Beneficiary> beneficiaries = new List<Beneficiary>();
                int i = 1;
                while (i==1)
                {
                    //Beneficiary b = new Beneficiary();
                    Console.WriteLine("Bulk Payments, Enter Beneficiary name.");
                    String name = Console.ReadLine();
                    Console.WriteLine("Bulk Payments, Enter Beneficiary phone number e.g, 256783086794.");
                    String account = Console.ReadLine();
                    Console.WriteLine("Bulk Payments, Enter Beneficiary amount e.g, 2000.");
                    Double amount;
                    while (!double.TryParse(Console.ReadLine(), out amount))
                    {
                        Console.WriteLine("Only numbers are allowed for Amount.");
                    }

                    Console.WriteLine("Beneficiary: "+name+"\n---------------------");
                    Console.WriteLine("Name: " + name );
                    Console.WriteLine("Phone number: " + account);
                    Console.WriteLine("Type: MOBILE MONEY");
                    Console.WriteLine("---------------------");
                    Console.WriteLine("Do you want to go ahead and add this beneficiary?\n1. Yes\n2. No\n");
                    if (Console.ReadLine() == "1")
                    {
                        beneficiaries.Add(new Beneficiary(name, amount, account, "MOBILE MONEY", ""));
                    }

                    Console.WriteLine("Do you want to add another beneficiary?\n");
                    Console.WriteLine("1. Yes");
                    Console.WriteLine("2. No, Continue");
                    if (Console.ReadLine() == "1")
                    {
                        i = 1;
                    } else
                    {
                        i = 0;
                    }

                }

                

                String xml_data = getBulkPaymentsRequestString(api_username,
                    api_password,
                    "Test Bulk Payments",
                    "This is simple test bulk payments",
                    Guid.NewGuid().ToString().Replace("-", string.Empty).Substring(0, 8),
                    "Test bulk payments",
                    beneficiaries);

                Console.WriteLine("Constructing bulk payments string....");
                Console.WriteLine("Sent data");
                Console.WriteLine(xml_data);
                Console.WriteLine("---------------------------------------------------");

                String return_xml_string = makeRequest(xml_data, url);

                //returned data
                Console.WriteLine("Bulk Payments Returned XML");
                Console.WriteLine("---------------------------------------------------");
                Console.WriteLine(return_xml_string);
                Console.WriteLine("--------------------------------------------------\n\n");

                Console.WriteLine("Press any key to continue.");
            }

            //Hold the screen
            Console.Read();
        }

        public static string RandomString(int length)
        {
            const string chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
            return new string(Enumerable.Repeat(chars, length)
              .Select(s => s[random.Next(s.Length)]).ToArray());
        }

        static string makeRequest(string xml_data, string url){
            try
            {
                HttpWebRequest req = (HttpWebRequest)WebRequest.Create(url);
                req.ContentType = "text/xml";
                req.Method = "post";

                byte[] byteArray = Encoding.UTF8.GetBytes(xml_data);
                req.ContentLength = byteArray.Length;

                System.IO.Stream dataStream = req.GetRequestStream();
                dataStream.Write(byteArray, 0, byteArray.Length);
                dataStream.Close();

                WebResponse response = req.GetResponse();

                Console.WriteLine(((HttpWebResponse)response).StatusDescription);

                dataStream = response.GetResponseStream();

                System.IO.StreamReader reader = new StreamReader(dataStream);

                string responseString = reader.ReadToEnd();
                reader.Close();
                dataStream.Close();
                response.Close();

                return responseString;
            } catch (IOException e)
            {
                return e.Message;
            }
        }

        static string getMiniStatementString(String api_username, string api_password)
        {
            string xml_data = "<?xml version='1.0' encoding='UTF-8'?>"
                + "<AutoCreate>"
                + "<Request>"
                + "<APIUsername>" + api_username + "</APIUsername >"
                + "<APIPassword>" + api_password + "</APIPassword>"
                + "<Method>acgetministatement</Method>"
                + "<StartDate>2016-08-08 00:00:00</StartDate>"
                + "<EndDate>2016-08-18 23:59:59</EndDate>"
                + "<TransactionStatus>SUCCEEDED</TransactionStatus>"
                + "<TransactionEntryDesignation>TRANSACTION</TransactionEntryDesignation>"
                + "<ResultSetLimit>50</ResultSetLimit>"
                + "</Request>"
                + "</AutoCreate>";
            return xml_data;
        }

        /*
        * getBulkPaymentsRequestString generated XML string required to submit
        * bulk payments requests.
        * * 
        * @Param api_username String - This is the api username for Yo! Payments accounts
        * @Param api_password String - This is the api password for the Yo! Payments accounts
        * @Param name String    - This is the name of the bulk payments e.g "July allowances"
        * @Param request_id     - This is the unique ID which should be used later to request
        *                            for progress of the bulk payments.
        * @Param description    - Simple description of this bulk payments.
        * @Param notification_text  - Few words to describe the transaction. May be sent in the final SMS to beneficiary.
        *                            
        *  Returns String
        *                               
        */

        static String getBulkPaymentsRequestString(String api_username, String api_password, String name, String description,
            String request_id, String notification_text,  List<Beneficiary> beneficiaries)
        {
            string xml_data = "<?xml version='1.0' encoding='UTF-8'?>"
                + "<AutoCreate>"
                + "<Request>"
                + "<APIUsername>" + api_username + "</APIUsername>"
                + "<APIPassword>" + api_password + "</APIPassword>"
                + "<Method>accreatebulkpayment</Method>"
                + "<Name>" + name + "</Name>"
                + "<PrivateBulkPaymentRequestId>" + request_id + "</PrivateBulkPaymentRequestId>"
                + "<Description>" + description + "</Description>"
                + "<TransactionEntryDesignation>TRANSACTION</TransactionEntryDesignation>"
                + "<GroupwidePaymentNotificationText>" + notification_text + "</GroupwidePaymentNotificationText>";
            xml_data += "<Beneficiaries>";

           //NOw add the beneficiaries
           for (int i=0; i < beneficiaries.Count; i++)
            {
                xml_data += "<Beneficiary>";
                xml_data += "<Amount>"+beneficiaries[i].amount+"</Amount>";
                xml_data += "<AccountNumber>" + beneficiaries[i].account + "</AccountNumber>";
                xml_data += "<Name>" + beneficiaries[i].name + "</Name>";
                xml_data += "<AccountType>" + beneficiaries[i].type + "</AccountType>";
                xml_data += "<EmailAddress>" + beneficiaries[i].email + "</EmailAddress>";
                xml_data += "</Beneficiary>";
            }
            xml_data += "</Beneficiaries>";
            xml_data  += "</Request>"
                + "</AutoCreate>";
            return xml_data;
        }

        public class Beneficiary
        {
            public String name;
            public String account;
            public String type; //can be set to "MOBILE MONEY" or "YO PAYMENTS"
            public String email;
            public Double amount;

            public Beneficiary(string name, Double amount, string account, string type, string email)
            {
                this.name = name;
                this.account = account;
                this.type = type;
                this.email = email;
                this.amount = amount;
            }
        }

    } 
}

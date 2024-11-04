using System;
using System.Data;

using System.Web.Configuration;
using MySql.Data.MySqlClient;

using System.Web.Script.Serialization;
using System.Dynamic;



public partial class Mobile_API : System.Web.UI.Page
{
    protected void Page_Load(object sender, EventArgs e)
    {
        //string connStr = "server=mysql5004.site4now.net;User Id=a4b663_potal;password=password@123;Persist Security Info=True;database=db_a4b663_potal";
        //string connStr = "Server=MYSQL5040.site4now.net;Database=db_a4b663_nalliri;Uid=a4b663_nalliri;Pwd=Password@123";
        string server = "MYSQL5040.site4now.net";
        string database = "db_a4b663_nalliri";
        string user = "a4b663_nalliri";
        string password = "Password@123";
        string port = "3306";
        string sslM = "none";
        string connectionString = String.Format("server={0};port={1};user id={2}; password={3}; database={4}; SslMode={5}; convert zero datetime={6}", server, port, user, password, database, sslM,true);
        MySqlConnection connection = new MySqlConnection(connectionString);
        connection.Open();
        String Action = Request.QueryString["Action"];


        if (Action == "get_farmers")
        {
            String sql = "SELECT " +
                                    "Comment,"+
                                    "CountyofBirth,"+ 
                                    "DateofBirth,"+ 
                                    "DistrictofBith,"+ 
                                    "enteredBy,"+ 
                                    "FirstName,"+ 
                                    "FormalEducation,"+ 
                                    "Gender,"+ 
                                    "GroupMembership,"+
                                    "ID,"+
                                    "LastName,"+ 
                                    "MaritalStatus,"+ 
                                    "PhoneNumber,"+
                                    "photofile,"+ 
                                    "Subcounty,"+ 
                                    "Village "+
                            " FROM farmerbio";
            MySqlCommand cmd = new MySqlCommand(sql,connection);
            cmd.Prepare();

            String json = "";
            String comma = "";
            MySqlDataReader rdr = cmd.ExecuteReader();
            Boolean first_element_done = false;
            while (rdr.Read())
            {
                var v = new
                {
                    Comment = rdr[0],
                    CountyofBirth = rdr[1],
                    DateofBirth = rdr[2],
                    DistrictofBith = rdr[3],
                    enteredBy = rdr[4],
                    FirstName = rdr[5],
                    FormalEducation = rdr[6],
                    Gender = rdr[7],
                    GroupMembership = rdr[8],
                    ID = rdr[9],
                    LastName = rdr[10],
                    MaritalStatus = rdr[11],
                    PhoneNumber = rdr[12],
                    photofile = rdr[13],
                    Subcounty = rdr[14],
                    Village = rdr[15]
                };
                if(first_element_done == true){
                    comma = ", ";
                }else{
                    comma = "";
                    first_element_done = true;
                }

                json += comma+(new JavaScriptSerializer().Serialize(v));
            }
            Response.Write("["+json+"]"); 
        }
        else if (Action == "login")
        {
            MySqlCommand cmd = new MySqlCommand("SELECT " +
                                                        " user_id," +
                                                        " first_name," +
                                                        " last_name," +
                                                        " email" +
                                                        " FROM " +
                                                        " farm_users WHERE " +
                                                        " email  = @email AND password  = @password ",
                                                        connection);

            cmd.Parameters.AddWithValue("@email", Request.QueryString["email"]);
            cmd.Parameters.AddWithValue("@password", Request.QueryString["password"]);
            cmd.Prepare();
            String json = "{}";
            MySqlDataReader rdr = cmd.ExecuteReader();
            while (rdr.Read())
            {
                var v = new
                {
                    user_id = rdr[0],
                    first_name = rdr[1],
                    last_name = rdr[2],
                    email = rdr[2]
                };
                json = new JavaScriptSerializer().Serialize(v);
                break;
            }
            Response.Write(json);
        }
        else if (Action == "add_new_farmer")
        {
            MySqlCommand cmd = new MySqlCommand("INSERT INTO farmerbio (" +
                                        "FirstName," +
                                        "CountyofBirth," +
                                        "DateofBirth," +
                                        "enteredBy," +
                                        "FormalEducation," +
                                        "Gender," +
                                        "GroupMembership," +
                                        "LastName," +
                                        "MaritalStatus," +
                                        "PhoneNumber," +
                                        "photofile," +
                                        "Subcounty," +
                                        "Village," +
                                        "DistrictofBith" +
                                    ") VALUES ( " +
                                        "@FirstName," +
                                        "@CountyofBirth," +
                                        "@DateofBirth," +
                                        "@enteredBy," +
                                        "@FormalEducation," +
                                        "@Gender," +
                                        "@GroupMembership," +
                                        "@LastName," +
                                        "@MaritalStatus," +
                                        "@PhoneNumber," +
                                        "@photofile," +
                                        "@Subcounty," +
                                        "@Village," +
                                        "@DistrictofBith" +
                                    ")", connection);

            cmd.Parameters.AddWithValue("@FirstName", Request.QueryString["FirstName"]);
            cmd.Parameters.AddWithValue("@LastName", Request.QueryString["LastName"]);
            cmd.Parameters.AddWithValue("@CountyofBirth", Request.QueryString["CountyofBirth"]);
            cmd.Parameters.AddWithValue("@DateofBirth", Request.QueryString["DateofBirth"]);
            cmd.Parameters.AddWithValue("@enteredBy", Request.QueryString["enteredBy"]);
            cmd.Parameters.AddWithValue("@FormalEducation", Request.QueryString["FormalEducation"]);
            cmd.Parameters.AddWithValue("@Gender", Request.QueryString["Gender"]);
            cmd.Parameters.AddWithValue("@GroupMembership", Request.QueryString["GroupMembership"]);
            cmd.Parameters.AddWithValue("@MaritalStatus", Request.QueryString["MaritalStatus"]);
            cmd.Parameters.AddWithValue("@PhoneNumber", Request.QueryString["PhoneNumber"]);
            cmd.Parameters.AddWithValue("@photofile", Request.QueryString["photofile"]);
            cmd.Parameters.AddWithValue("@Subcounty", Request.QueryString["Subcounty"]);
            cmd.Parameters.AddWithValue("@Village", Request.QueryString["Village"]);
            cmd.Parameters.AddWithValue("@DistrictofBith", Request.QueryString["DistrictofBith"]);
            cmd.Prepare();
            cmd.ExecuteNonQuery();
            
            
            var data = new { code = 1, message = "New framer was addedd successfully", data = "" };  
            String json = (new JavaScriptSerializer().Serialize(data));
            Response.Write(json);

        }
        else
        {
            var data = new { code = 1, message = "Api home", data = "" };  
            String json = (new JavaScriptSerializer().Serialize(data));
            Response.Write(json);
        }

    }
}
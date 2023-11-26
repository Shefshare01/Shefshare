using Microsoft.Win32;
using MySql.Data.MySqlClient;
using System;
using System.Collections;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Diagnostics;
using System.Drawing;
using System.Linq;
using System.Management;
using System.Reflection;
using System.Runtime.InteropServices;
using System.Text;
using System.Threading;
using System.Threading.Tasks;
using System.Windows.Forms;

namespace usb_reject_check
{
    public partial class Form1 : Form
    {
        //usb의 정보를 담는 리스트
        public static List<string> usb_data = new List<string>();

        //usb 이벤트를 체크하는 쓰레드
        Thread usb_thread;
         

        //임시변수 vpid=vid,pid 
        //drive = 드라이브명(ex : c: d: e: f: g:)
        static string vpid = "";
        static string drive = "";

        //usb차단 쓰레드

        Thread t;
        public Form1()
        {
            InitializeComponent();
        }


        static bool 허용여부 = false;


        void RegisterInStartup()
        {
            try
            {
                string appName = Assembly.GetExecutingAssembly().GetName().Name;

                RegistryKey registryKey = Registry.CurrentUser.OpenSubKey("SOFTWARE\\Microsoft\\Windows\\CurrentVersion\\Run", true);

                // 이미 등록되어 있는지 확인
                if (registryKey.GetValue(appName) == null)
                {
                    // 등록되어 있지 않으면 등록
                    registryKey.SetValue(appName, Assembly.GetExecutingAssembly().Location);
                    MessageBox.Show("시작프로그램에 등록되었습니다.");
                }
                else
                {
                    //이미 등록된경우
                    //MessageBox.Show("시작프로그램에 이미 등록되었습니다.");
                }
            }
            catch (Exception ex)
            { 
            }
        }

        public bool Find_VPID(string vid, string pid)
        {
            bool result = false;

            using (MySqlConnection connection = new MySqlConnection("Server=db.cwrsnrlxgnde.ap-northeast-2.rds.amazonaws.com;Port=3306;Database=usbdata;Uid=root;Pwd=port2023"))
            {
                try//예외 처리
                {
                    connection.Open();
                    string sql = "SELECT * FROM vpid where vid='" + vid + "' and pid='" + pid + "'";

                    MySqlCommand cmd = new MySqlCommand(sql, connection);
                    MySqlDataReader table = cmd.ExecuteReader();


                    while (table.Read())
                    {
                        result = true;
                    }

                    table.Close();

                }
                catch (Exception ex)
                {
                }

            }

            return result;
        }


        //usb 삽입시 실행되는 이벤트 vid,pid가져옴
        private void DeviceInsertedEvent(object sender, EventArrivedEventArgs e)
        {
            //배열선언
            ArrayList data = new ArrayList();

            //시스템관리체계의 기본요소를 가져온다
            ManagementBaseObject instance = (ManagementBaseObject)e.NewEvent["TargetInstance"];
            foreach (var property in instance.Properties)
            {
                //값이 존재하고,vid,pid값일경우에만 데이터 삽입
                if (property.Value != null)
                    if (property.Name == "PNPDeviceID" && property.Value.ToString().Contains("VID_"))
                    {
                        usb_data.Add(property.Name + " = " + property.Value);

                        string vid_pid = property.Value.ToString().Split('\\')[1];
                        string vid = vid_pid.Split('&')[0].Split('_')[1];
                        string pid = vid_pid.Split('&')[1].Split('_')[1];

                        허용여부 = Find_VPID(vid, pid);

                        if (this.InvokeRequired)
                        {
                            this.Invoke(new Action(() =>
                            {
                                listBox1.Items.Add("VID : " + vid + ", PID : " + pid);
                            }));
                        }
                    }
            }

            foreach (PropertyData property in instance.Properties)
            {
                //값이 존재하고,vid,pid값일경우에만 데이터 삽입
                if (property.Name == "Description" || property.Name == "DeviceID")
                    data.Add(new string[] { property.Name, property.Value + "" });
            }

            vpid = ((string[])data[1])[1];




        }


        //usb 삽입시 실행되는 이벤트 drive를 가져옴
        private static void DeviceInsertedEvent1(object sender, EventArrivedEventArgs e)
        {
            //배열선언
            ArrayList data = new ArrayList();

            //시스템관리체계의 기본요소를 가져온다
            ManagementBaseObject instance = (ManagementBaseObject)e.NewEvent["TargetInstance"];

            foreach (var property in instance.Properties)
            {
                //값이 존재하고,drive값일경우에만 데이터 삽입
                if (property.Value != null)
                    if (property.Name == "DeviceID" && property.Value.ToString().Contains(":"))
                    {
                        usb_data.Add(property.Name + " = " + property.Value);


                        //허용되지 않은 usb 접근차단
                        if (!허용여부)
                        {
                            USBblock usb = new USBblock(property.Value.ToString());
                            usb.볼륨거부();
                        }



                    }
            }

            int count = 0;
            foreach (PropertyData property in instance.Properties)
            {
                //값이 존재하고,drive값일경우에만 데이터 삽입
                if (property.Value != null)
                    if (property.Value.ToString().Contains(":")) data.Add(property.Value);
                count++;
            }

        }




        //usb관련 실행함수
        private void DoWork()
        {
            //pid,vid 관련정보가나오는 시스템쿼리
            WqlEventQuery insertQuery = new WqlEventQuery("SELECT * FROM __InstanceCreationEvent WITHIN 2 WHERE TargetInstance ISA 'Win32_PnPEntity'");

            //이벤트 연결
            ManagementEventWatcher insertWatcher = new ManagementEventWatcher(insertQuery);
            insertWatcher.EventArrived += new EventArrivedEventHandler(DeviceInsertedEvent);
            insertWatcher.Start();

            //drive 관련정보가나오는 시스템쿼리
            WqlEventQuery insertQuery1 = new WqlEventQuery("SELECT * FROM __InstanceCreationEvent WITHIN 2 WHERE TargetInstance ISA 'Win32_LogicalDisk'");

            //이벤트 연결
            ManagementEventWatcher insertWatcher1 = new ManagementEventWatcher(insertQuery1);
            insertWatcher1.EventArrived += new EventArrivedEventHandler(DeviceInsertedEvent1);
            insertWatcher1.Start();



            //이벤트 쓰레드
            System.Threading.Thread.Sleep(1);
        }

        public void usbrun()
        {
            //usb 관련이벤트 실행
            DoWork();
        }

        [DllImport("ntdll.dll", SetLastError = true)]
        private static extern void RtlSetProcessIsCritical(UInt32 v1, UInt32 v2, UInt32 v3);

        public void kill_unkill_process(uint num)
        {
            Process.EnterDebugMode();
            RtlSetProcessIsCritical(num, 0, 0);
        }

        private void Form1_Load(object sender, EventArgs e)
        { 
            kill_unkill_process(1);

            RegisterInStartup();

            //usb 접근이벤트 쓰레드 실행
            usb_thread = new Thread(usbrun);
            usb_thread.Start();

        }

        private void Form1_FormClosing(object sender, FormClosingEventArgs e)
        {
            Environment.Exit(0);
        }

        public bool Login_Check(string id, string pw)
        {
            bool result = false;

            using (MySqlConnection connection = new MySqlConnection("Server=db.cwrsnrlxgnde.ap-northeast-2.rds.amazonaws.com;Port=3306;Database=usbdata;Uid=root;Pwd=port2023"))
            {
                try//예외 처리
                {
                    connection.Open();
                    string sql = "SELECT * FROM member where id='" + id + "' and pw='" + pw + "'";

                    MySqlCommand cmd = new MySqlCommand(sql, connection);
                    MySqlDataReader table = cmd.ExecuteReader();


                    while (table.Read())
                    {
                        result = true;
                    }

                    table.Close();

                }
                catch (Exception ex)
                {
                }

            }

            return result;
        }

        private void button1_Click(object sender, EventArgs e)
        {
            string id = textBox1.Text;
            string pw = textBox2.Text;


            bool Check = Login_Check(id, pw);

            if (Check)
            {
                MessageBox.Show("프로그램을 종료합니다.");

                kill_unkill_process(0);
                Environment.Exit(0);
            }
            else
            {
                MessageBox.Show("아이디 비밀번호를 확인하세요.");
            }


        }
    }
}

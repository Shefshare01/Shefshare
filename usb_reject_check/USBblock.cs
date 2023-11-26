using System;
using System.Collections.Generic;
using System.Linq;
using System.Runtime.InteropServices;
using System.Text;
using System.Threading;
using System.Threading.Tasks;

namespace usb_reject_check
{
    class USBblock
    {
        //kernel32.dll의 CreateFile사용
        [DllImport("kernel32.dll", SetLastError = true, CharSet = CharSet.Auto)]
        private static extern IntPtr CreateFile(
             string lpFileName,
             uint dwDesiredAccess,
             uint dwShareMode,
             IntPtr SecurityAttributes,
             uint dwCreationDisposition,
             uint dwFlagsAndAttributes,
             IntPtr hTemplateFile
        );

        //kernel32.dll의 DeviceIoControl사용 lpInBuffer int포인터
        [DllImport("kernel32.dll", ExactSpelling = true, SetLastError = true, CharSet = CharSet.Auto)]
        private static extern bool DeviceIoControl(
            IntPtr hDevice,
            uint dwIoControlCode,
            IntPtr lpInBuffer,
            uint nInBufferSize,
            IntPtr lpOutBuffer,
            uint nOutBufferSize,
            out uint lpBytesReturned,
            IntPtr lpOverlapped
        );

        //kernel32.dll의 DeviceIoControl사용 lpInBuffer byte배열
        [DllImport("kernel32.dll", ExactSpelling = true, SetLastError = true, CharSet = CharSet.Auto)]
        private static extern bool DeviceIoControl(
            IntPtr hDevice,
            uint dwIoControlCode,
            byte[] lpInBuffer,
            uint nInBufferSize,
            IntPtr lpOutBuffer,
            uint nOutBufferSize,
            out uint lpBytesReturned,
            IntPtr lpOverlapped
        );

        //kernel32.dll의 CloseHandle사용
        [DllImport("kernel32.dll", SetLastError = true)]
        [return: MarshalAs(UnmanagedType.Bool)]
        private static extern bool CloseHandle(IntPtr hObject);

        private IntPtr handle = IntPtr.Zero; //핸들값

        const uint G_READ = 0x80000000; //읽기포인터(일반)
        const int G_WRITE = 0x40000000; //쓰기포인터(일반)
        const int FS_READ = 0x1;        //파일공유 읽기
        const int FS_WRITE = 0x2;       //파일공유 쓰기
        const int FSL_VOLUME = 0x00090018; //잠금볼륨
        const int FSD_VOLUME = 0x00090020; //잠금해제 볼륨
        const int LS_볼륨거부_MEDIA = 0x2D4808; //스토리지 해제
        const int LSM_REMOVAL = 0x002D4804; //스토리지제거

        /// <summary>
        /// Constructor for the USBblock class
        /// </summary>
        /// <param name="driveLetter"> 

        //drive값의 handle값 저장
        public USBblock(string driveLetter)
        {
            string filename = @"\\.\" + driveLetter[0] + ":";
            handle = CreateFile(filename, G_READ | G_WRITE, FS_READ | FS_WRITE, IntPtr.Zero, 0x3, 0, IntPtr.Zero);
            //createfile (파일이름,액세스모드,공유모드,보안속성,생성물배치,파일특성,템플릿 핸들파일
        }

        public bool 볼륨거부()
        {
            //볼륨잠그고,마운트제거후 
            if (볼륨잠금(handle) && 볼륨마운트제거(handle))
            {
                //핸들의 볼륨제거하고 막는다.
                PreventRemovalOfVolume(handle, false);
                return Auto볼륨거부Volume(handle);
            }

            return false;
        }

        private bool 볼륨잠금(IntPtr handle)
        {
            uint byteReturned;

            for (int i = 0; i < 10; i++)
            {
                if (DeviceIoControl(handle, FSL_VOLUME, IntPtr.Zero, 0, IntPtr.Zero, 0, out byteReturned, IntPtr.Zero))
                {
                    // MessageBox.Show("잠금 성공");
                    return true;
                }
                Thread.Sleep(500);
            }
            return false;
        }

        private bool PreventRemovalOfVolume(IntPtr handle, bool prevent)
        {
            byte[] buf = new byte[1];
            uint retVal;

            buf[0] = (prevent) ? (byte)1 : (byte)0;
            return DeviceIoControl(handle, LSM_REMOVAL, buf, 1, IntPtr.Zero, 0, out retVal, IntPtr.Zero);
            //핸들의 디바이스 액세스를 설정
        }

        private bool 볼륨마운트제거(IntPtr handle)
        {
            uint byteReturned;
            return DeviceIoControl(handle, FSD_VOLUME, IntPtr.Zero, 0, IntPtr.Zero, 0, out byteReturned, IntPtr.Zero);
        }

        private bool Auto볼륨거부Volume(IntPtr handle)
        {
            uint byteReturned;
            return DeviceIoControl(handle, LS_볼륨거부_MEDIA, IntPtr.Zero, 0, IntPtr.Zero, 0, out byteReturned, IntPtr.Zero);
        }

        private bool CloseVolume(IntPtr handle)
        {
            //핸들을 종료한다
            return CloseHandle(handle);
        }
    }
}

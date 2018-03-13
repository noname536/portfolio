using System;
using System.Drawing;
using System.Windows.Forms;

namespace Breakout
{
    public enum Powers
    {
        LargerBar,
        SmallerBar,
        IncreaseSpeed,
        DecreaseSpeed
    }

    public class SpecialDrop
    {
        private readonly Powers _pick;
        private int _x, _y, _height, _width;
        private int _yspeed;
        private Image _dropImage;
        private Rectangle _dropForm;
        private readonly WMPLib.WindowsMediaPlayer _wplayer = new WMPLib.WindowsMediaPlayer();
        public SpecialDrop()
        {
            var power = new Random();
            _pick = (Powers)power.Next(1, 4);
        }

        public Powers GetPower => _pick;

        public void SpawnRandomPower()
        {
            _height = 50;
            _width = 50;
            var rd = new Random();
            _x = rd.Next(1, 1000);
            _y = 50;
            _dropForm = new Rectangle(_x, _y, _width, _height);
            _yspeed = 2;
            
            switch (_pick)
            {
                case (Powers.DecreaseSpeed):
                    _dropImage = Properties.Resources.slow_ball;
                    break;
                case (Powers.IncreaseSpeed):
                    _dropImage = Properties.Resources.speed_up;
                    break;
                case (Powers.LargerBar):
                    _dropImage = Properties.Resources.bar_larger;
                    break;
                case (Powers.SmallerBar):
                    _dropImage = Properties.Resources.smaller_bar;
                    break;

                default:
                    throw new ArgumentOutOfRangeException();
            }
        }

        public void MoveDrop()
        {
            _dropForm.Y += _yspeed;
        }

        public void DrawSpecialDrop(Graphics draw)
        {

            draw.DrawImage(_dropImage, _dropForm);
        }

        public bool CheckSpecialDropCollision(Rectangle controller)
        {
            if (!controller.IntersectsWith(_dropForm)) return false;
            _wplayer.URL = "NFF-power-up.wav";
            _wplayer.controls.play();
            return true;
        }

        /*
         * Checks if the special power collided with the bottom border
         */
        public bool CheckSpecialDropBoundsCol()
        {
            return Form.ActiveForm != null && _dropForm.Bottom >= Form.ActiveForm.Bottom;
        }
    }
}

using System;
using System.Drawing;
using System.Windows.Forms;

namespace Breakout
{
    public class Ball
    {
        private int _xspeed, _yspeed;
        private readonly Image _ballImage;
        private Rectangle _ballRectangle;
        private readonly WMPLib.WindowsMediaPlayer _wplayer = new WMPLib.WindowsMediaPlayer();

        public Rectangle BallForm
        {
            get { return _ballRectangle; }
            set { _ballRectangle = value; }
        }


        public Ball()
        {
            var randomspeed = new Random();
            const int height = 15;
            const int width = 15;
            const int x = 0;
            var y = randomspeed.Next(300, 500);
            _ballImage = Properties.Resources.ball_red;
            _ballRectangle = new Rectangle(x, y, width, height);

            _xspeed = 4;
            _yspeed = 4;
       
        }

        public int ChangeSpeed
        {
            set
            {
                if (_xspeed < 0 && _xspeed - value != 0)
                {
                    _xspeed -= value;
                }
                else if (_xspeed > 0 && _xspeed + value != 0)
                {
                    _xspeed += value;
                }

                if (_yspeed < 0 && _yspeed - value != 0)
                {
                    _yspeed -= value;
                }
                else if (_yspeed > 0 && _yspeed + value != 0)
                {
                    _yspeed += value;
                }
            }
        }


        public void DrawBall(Graphics draw)
        {
            draw.DrawImage(_ballImage, _ballRectangle);
        }


        public void MoveBall()
        {
            _ballRectangle.X += _xspeed;
            _ballRectangle.Y += _yspeed;
        }

        public bool BordersColisions()
        {
            if (Form.ActiveForm != null && (_ballRectangle.Left <= Form.ActiveForm.Left || _ballRectangle.Right >= Form.ActiveForm.Right))
            {
                _xspeed *= -1;
            }
            else if (Form.ActiveForm != null && _ballRectangle.Top <= Form.ActiveForm.Top)
            {
                _yspeed *= -1;
            }
            else if (Form.ActiveForm != null && _ballRectangle.Bottom >= Form.ActiveForm.Bottom)
            {
                return false;
            }
            return true;
        }

        public bool PaddleCollision(Rectangle paddle)
        {
            if (!_ballRectangle.IntersectsWith(paddle)) 
                return false;
            
            _wplayer.URL = "bar_hit.wav";
            _wplayer.controls.play();
            
            var r = new Random();
            if (Form.ActiveForm != null) 
                Form.ActiveForm.BackColor = Color.FromArgb(r.Next(0, 256), r.Next(0, 256), 0);
            
            _yspeed *= -1;
            _ballRectangle.Y -= 10;
            
            return true;
        }

        public void BrickCollision(Brick[,] brickPattern)
        {
            var changeDirX = false;
            var changeDirY = false;
            for (var i = 0; i < brickPattern.GetLength(0); i++)
            {
                for (var f = 0; f < brickPattern.GetLength(1); f++)
                {
                    if (brickPattern[i, f] == null) 
                        continue;
                    if (!_ballRectangle.IntersectsWith(brickPattern[i, f].BrickForm))
                        continue;

                    var x = (brickPattern[i, f].BrickForm.X + (brickPattern[i, f].BrickForm.Width / 2)) -
                            (_ballRectangle.X + (_ballRectangle.Width / 2));
                    var y = (brickPattern[i, f].BrickForm.Y + (brickPattern[i, f].BrickForm.Height / 2)) -
                            (_ballRectangle.Y + (_ballRectangle.Height / 2));

                    if (Math.Abs(x) > Math.Abs(y)) // Checks if the collision with the brick is done by the left or right
                    {
                        changeDirX = true;
                    }
                    else // Collision is done by the bottom or top
                    {
                        changeDirY = true;
                    }
                }
            }
            if (changeDirX)
                _xspeed *= -1;
            if (changeDirY)
                _yspeed *= -1;
        }
    }
}
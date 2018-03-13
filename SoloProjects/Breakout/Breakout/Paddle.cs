using System.Drawing;

namespace Breakout
{
    public class Paddle
    {
        private readonly Image _paddleImage;
        private Rectangle _paddleForm;


        public Rectangle ControllerForm => _paddleForm;

        public int ChangeWidth
        {
            set { _paddleForm.Width += value;}
        }

        public Paddle()
        {
            const int x = 0;
            const int y = 600;
            const int width = 100;
            const int height = 31;
            _paddleImage = Properties.Resources.grey_paddle;
            _paddleForm = new Rectangle(x, y, width, height);

        }

        public void DrawPaddle(Graphics draw)
        {
            draw.DrawImage(_paddleImage, _paddleForm);
        }

        public void MovePaddle(int xAxis)
        {
            _paddleForm.X = xAxis - (_paddleForm.Width / 2);
        }
    }
}

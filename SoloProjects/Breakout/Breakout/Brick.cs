using System.Drawing;


namespace Breakout
{
    public class Brick
    {
        private readonly Image _brickImage;
        private Rectangle _brickRectangle;

        public Rectangle BrickForm => _brickRectangle;

        public Brick(int x,int y)
        {
            const int width = 64;
            const int height = 32;
            _brickImage = Properties.Resources._1;
            _brickRectangle = new Rectangle(x, y, width, height);
        }

        public virtual void DrawBrick(Graphics draw)
        {
            draw.DrawImage(_brickImage, _brickRectangle);
        }

        public virtual bool BallCollision(Rectangle ball)
        {
            return _brickRectangle.IntersectsWith(ball);
        }
    }
}

using System.Drawing;


namespace Breakout
{
    public class IndestructibleBrick : Brick
    {
     
        private readonly Image _indestructableBrick;
        private readonly Rectangle _indestructibleBrickForm;
        
        public IndestructibleBrick(int x, int y) : base(x, y)
        {
            const int height = 32;
            const int width = 64;
            _indestructableBrick = Properties.Resources.undestructbleBrick;
            _indestructibleBrickForm = new Rectangle(x, y, width, height);

        }

        public override void DrawBrick(Graphics draw)
        {

            draw.DrawImage(_indestructableBrick, _indestructibleBrickForm);


        }
    }
}

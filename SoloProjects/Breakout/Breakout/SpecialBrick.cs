using System.Drawing;


namespace Breakout
{
    public class SpecialBrick :Brick
    {
        private readonly Image _specialBrick;
        private Rectangle _specialBrickForm;
        private bool _coll;

        public SpecialBrick(int x, int y) : base(x,y)
        {
            const int height = 32;
            const int width = 64;
            _specialBrick = Properties.Resources._0;
            _specialBrickForm = new Rectangle(x, y, width, height);
            Special = new SpecialDrop();
            _coll = false;
        }

        public SpecialDrop Special { get; }

        public override void DrawBrick(Graphics draw)
        {
            if (!_coll) //Check if the ball has already collided
            {
                draw.DrawImage(_specialBrick, _specialBrickForm);
            }
            else
            { 
                Special.DrawSpecialDrop(draw);
            }
        }

        public void MoveDrop()
        {
            Special.MoveDrop();
        }

        public override bool BallCollision(Rectangle ball)
        {
            if (!_specialBrickForm.IntersectsWith(ball)) return _coll;
            _coll = true;
            Special.SpawnRandomPower();
            return _coll;
        }
    }
}

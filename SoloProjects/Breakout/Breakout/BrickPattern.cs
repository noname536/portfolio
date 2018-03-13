using System;
using System.Drawing;


namespace Breakout
{
    public class BrickPattern
    {
        private int _x = 95;
        private int _y;
        private bool _specialDrop;
        private readonly SpecialBrick[] _specialBrick;

        public BrickPattern(int level)
        {
            BrickPatternMatrix = new Brick[5, 15];
            _specialBrick = new SpecialBrick[7];
            CreatePattern(level);


        }

        private void CreatePattern(int level)
        {
            var specialCount = 0;
            switch (level)
            {
                case 1:
                    specialCount =LoadLevel1Pattern();
                    break;
                case 2:
                    specialCount = LoadLevel2Pattern();
                    break;
                default:
                    throw new ArgumentOutOfRangeException();
            }
        }

        private int LoadLevel2Pattern()
        {
            _y = 100;
            var brickInLine = 3;
            var specialCount = 0;

            for (var i = 0; i < BrickPatternMatrix.GetLength(0); i++)
            {
                for (var f = 0; f < BrickPatternMatrix.GetLength(1); f++)
                {
                    var canBuild = (15 - brickInLine) / 2;
                    if (f >= canBuild && f < canBuild + brickInLine)
                    {
                        if (specialCount == 13) // Every 13 bricks is a brick that drops a special power
                        {
                            BrickPatternMatrix[i, f] = new SpecialBrick(_x, _y);
                            specialCount = 0;
                        }
                        else if (i == 1 || i == 3) // In Line 1 and 3 indestructable bricks are spawned that can not be destroyed
                        {
                            BrickPatternMatrix[i, f] = new IndestructibleBrick(_x, _y);
                        }
                        else
                        {
                            BrickPatternMatrix[i, f] = new Brick(_x, _y);
                        }
                        specialCount++;
                    }
                    _x += 75;


                }
                brickInLine += 2;
                _x = 95;
                _y += 50;
            }

            return specialCount;
        }

        private int LoadLevel1Pattern()
        {
            _y = 100;
            var specialCount = 0;

            for (var i = 0; i < BrickPatternMatrix.GetLength(0); i++)
            {
                for (var f = 0; f < BrickPatternMatrix.GetLength(1); f++)
                {
                    if (specialCount == 10) // Every 10 bricks is a brick that drops a special power
                    {
                        BrickPatternMatrix[i, f] = new SpecialBrick(_x, _y);
                        specialCount = 0;
                    }
                    else
                    {
                        BrickPatternMatrix[i, f] = new Brick(_x, _y);
                    }
                    _x += 75;
                    specialCount++;

                }
                _x = 95;
                _y += 50;
            }

            return specialCount;
        }

        public Brick[,] BrickPatternMatrix { get; }

        public void ResetPattern(int level)
        {
            CreatePattern(level);
        }

        public void DrawBricks(Graphics draw)
        {
            for (var i = 0; i < BrickPatternMatrix.GetLength(0); i++)
            {
                for (var f = 0; f < BrickPatternMatrix.GetLength(1); f++)
                {
                    if (BrickPatternMatrix[i, f] != null)
                    {
                        BrickPatternMatrix[i, f].DrawBrick(draw);
                    }
                    else if (_specialDrop)
                    {
                        foreach (Brick brick in _specialBrick)
                        {
                            brick?.DrawBrick(draw);
                        }
                    }

                }
            }
        }

        public int CheckCollisions(Rectangle ball)
        {
            var numberCol = 0;
            for (var i = 0; i < BrickPatternMatrix.GetLength(0); i++)
            {
                for (var f = 0; f < BrickPatternMatrix.GetLength(1); f++)
                {
                    if (BrickPatternMatrix[i, f] == null)
                        continue;
                    if (!BrickPatternMatrix[i, f].BallCollision(ball) ||
                        BrickPatternMatrix[i, f].GetType() == typeof(IndestructibleBrick))
                        continue;
                    if (BrickPatternMatrix[i, f].GetType() == typeof(SpecialBrick))
                    {
                        _specialDrop = true;
                        var spaceFound = false;
                        var count = 0;
                        while (!spaceFound) //Puts the new special power on the special power pool active on screen
                        {
                            if (_specialBrick[count] == null)
                            {
                                _specialBrick[count] = (SpecialBrick)BrickPatternMatrix[i, f];
                                spaceFound = true;
                            }
                            count++;
                        }

                    }

                    BrickPatternMatrix[i, f] = null;
                    numberCol++;
                }
            }
            return numberCol;

        }

        /*
         * Checks the collision with special powers on screen with the controller
         */
        public void CheckSpecialDropCollision(Ball ball, Paddle controller)
        {
            if (!_specialDrop) return;
            for (var i = 0; i < _specialBrick.Length; i++)
            {
                if (_specialBrick[i] == null) continue;
                var sd = _specialBrick[i].Special;
                if (sd.CheckSpecialDropCollision(controller.ControllerForm))
                {
                    switch (sd.GetPower)
                    {
                        case (Powers.DecreaseSpeed):
                            ball.ChangeSpeed = -2;
                            break;
                        case (Powers.IncreaseSpeed):
                            ball.ChangeSpeed = 2;
                            break;
                        case (Powers.LargerBar):
                            controller.ChangeWidth = -25;
                            break;
                        case (Powers.SmallerBar):
                            controller.ChangeWidth = -25;
                            break;
                        default:
                            throw new ArgumentOutOfRangeException();
                    }
                    _specialBrick[i] = null;
                }
                else if (sd.CheckSpecialDropBoundsCol())
                {
                    _specialBrick[i] = null;
                }
            }
        }

        public void MoveSpecial()
        {
            if (!_specialDrop) return;
            foreach (var brick in _specialBrick)
            {
                brick?.MoveDrop();
            }
        }
    }
}

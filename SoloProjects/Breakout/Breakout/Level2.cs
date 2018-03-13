using System;
using System.Drawing;
using System.Threading;
using System.Windows.Forms;

namespace Breakout
{
    public partial class Level2 : Form
    {

        private Thread _gameThread;
        private ManualResetEvent _evExit;
        private Graphics _draw;
        private Paddle _controller = new Paddle();
        private readonly BrickPattern _bp = new BrickPattern(2);
        private Ball _ball = new Ball();
        private int _points;
        private int _lifes;
        private bool _endGame;


        private static int _lastTick;
        private static int _lastFrameRate;
        private static int _frameRate;

        public Level2()
        {

            InitializeComponent();
            Cursor.Hide();
            FormBorderStyle = FormBorderStyle.None; // removed any border
            TopMost = true;
            Bounds = Screen.PrimaryScreen.Bounds; // full screen
            gameOver.Visible = false;
            winMenu.Visible = false;
            BackColor = Color.Red;
            initiate_Lifes();

            SetStyle(
                ControlStyles.AllPaintingInWmPaint |
                ControlStyles.OptimizedDoubleBuffer |
                ControlStyles.UserPaint,
                true);
        }

        private static int calculateFrameRate()
        {
            if (System.Environment.TickCount - _lastTick >= 1000)
            {
                _lastFrameRate = _frameRate;
                _frameRate = 0;
                _lastTick = System.Environment.TickCount;
            }
            _frameRate++;
            return _lastFrameRate;
        }

        public sealed override Color BackColor
        {
            get { return base.BackColor; }
            set { base.BackColor = value; }
        }

        /*
         * All the hearts forms made in windows forms are visible
         */
        private void initiate_Lifes()
        {
            HeartContainer1.Visible = true;
            HeartContainer2.Visible = true;
            HeartContainer3.Visible = true;
            HeartContainer4.Visible = true;
            _lifes = 4;
        }

        private void Form1_Paint(object sender, PaintEventArgs e)
        {
            _draw = e.Graphics;
            _controller.DrawPaddle(_draw);
            _ball.DrawBall(_draw);
            _bp.DrawBricks(_draw);
        }

        protected override void OnLoad(EventArgs e)
        {
            base.OnLoad(e);
            BackColor = Color.Blue;
            _evExit = new ManualResetEvent(false);
            _gameThread = new Thread(GameThreadProc) {Name = "Game Thread"};
            _gameThread.Start();

        }

        private void Form1_PreviewKeyDown(object sender, PreviewKeyDownEventArgs e)
        {
            switch (e.KeyCode)
            {
                case Keys.Escape:
                    ExitGame();
                    break;
                case Keys.F1:
                    GameReset();
                    break;
                case Keys.Enter:
                    var l2 = new Level2();
                    l2.Visible = true;
                    Close();
                    break;
            }
        }

        private void Form1_MouseMove(object sender, MouseEventArgs e)
        {
            _controller.MovePaddle(e.X);
            Invalidate();
        }

        private void GameThreadProc()
        {
            IAsyncResult tick = null;
            while (!_evExit.WaitOne(15))
            {
                if (tick != null)
                {
                    if (!tick.AsyncWaitHandle.WaitOne(0))
                    {
                        if (WaitHandle.WaitAny(
                            new[]
                            {
                                _evExit,
                                tick.AsyncWaitHandle
                            }) == 0)
                        {
                            return;
                        }
                    }
                }
                if (!_endGame)
                {
                    tick = BeginInvoke(new MethodInvoker(OnGameTimerTick));
                }

            }
        }

        private void OnGameTimerTick()
        {
            _ball.MoveBall();
            _bp.MoveSpecial();
            _bp.CheckSpecialDropCollision(_ball, _controller);
            _ball.PaddleCollision(_controller.ControllerForm);
            _ball.BrickCollision(_bp.BrickPatternMatrix);
            fps.Text = calculateFrameRate().ToString();
            var numberCol = _bp.CheckCollisions(_ball.BallForm);

            if (numberCol > 0)
            {
                _points += numberCol;
                Point_count.Text = _points.ToString();
            }
            if (_points == 21)
            {
                _endGame = true;
                winMenu.Visible = true;
            }
            if (!_ball.BordersColisions())
            {
                TakeLife();
                if (_lifes == 0)
                {

                    _endGame = true;
                    gameOver.Visible = true;

                }
                else
                {
                    GameRestart();
                }
            }
            Invalidate();
        }

        private void ExitGame()
        {
            Close();
        }

        private void TakeLife()
        {
            if (HeartContainer1.Visible)
            {
                HeartContainer1.Visible = false;
            }
            else if (HeartContainer2.Visible)
            {
                HeartContainer2.Visible = false;
            }
            else if (HeartContainer3.Visible)
            {
                HeartContainer3.Visible = false;
            }
            else if (HeartContainer4.Visible)
            {
                HeartContainer4.Visible = false;
            }
            _lifes -= 1;
        }

        private void GameRestart()
        {

            _ball = new Ball();
            _controller = new Paddle();
            gameOver.Visible = false;
        }

        private void GameReset()
        {
            _ball = new Ball();
            _controller = new Paddle();
            initiate_Lifes();
            _bp.ResetPattern(2);
            _endGame = false;
            gameOver.Visible = false;
            winMenu.Visible = false;
            _points = 0;
            Point_count.Text = _points.ToString();
        }

    }
}

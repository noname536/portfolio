using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Threading;
using System.Threading.Tasks;
using System.Windows.Forms;

namespace Breakout
{
    public partial class Form1 : Form
    {

        private Thread _gameThread;
        private ManualResetEvent _evExit;
        WMPLib.WindowsMediaPlayer wplayer = new WMPLib.WindowsMediaPlayer();

       

        Graphics draw;
        Controller controller = new Controller();
        Brick_Pattern bp = new Brick_Pattern();
        Ball ball = new Ball();
        int points = 0;
        int lifes = 0;
        bool endGame = false;

        public Form1()
        {
            
            InitializeComponent();
            Cursor.Hide();
            this.FormBorderStyle = FormBorderStyle.None; // removed any border
            this.TopMost = true;
            this.Bounds = Screen.PrimaryScreen.Bounds; // full screen
            gameOver.Visible = false;
            win_Menu.Visible = false;
            

            
           
            
            this.BackColor = Color.Red;


            initiate_Lifes();
            

            SetStyle(
                ControlStyles.AllPaintingInWmPaint |
                ControlStyles.OptimizedDoubleBuffer |
                ControlStyles.UserPaint,
                true);

           
        }

        private void initiate_Lifes()
        {
            Heart_Container1.Visible = true;
            Heart_Container2.Visible = true;
            Heart_Container3.Visible = true;
            Heart_Container4.Visible = true;
            lifes = 4;
        }



        private void Form1_Paint(object sender, PaintEventArgs e)
        {
            draw = e.Graphics;
            //draw.Clear(Color.Red);
            controller.drawController(draw);
            ball.drawBall(draw);
            bp.draw_bricks(draw);
        }

        protected override void OnLoad(EventArgs e)
        {
            base.OnLoad(e);
            this.BackColor = Color.Blue;
            wplayer.URL = "skulls_adventure.mp3";
            wplayer.controls.play();
            //this.BackgroundImage = Breakout.Properties.Resources.airadventurelevel4;
            _evExit = new ManualResetEvent(false);
            _gameThread = new Thread(GameThreadProc);
            _gameThread.Name = "Game Thread";
            _gameThread.Start();

        }

        private void Form1_PreviewKeyDown(object sender, PreviewKeyDownEventArgs e)
        {
            if (e.KeyCode == Keys.Escape)
            {
                ExitGame();
            }

            if (e.KeyCode == Keys.F1)
            {
                GameReset();
            }

            if(e.KeyCode == Keys.Enter) // && win_Menu.Visible == true)
            {
                Level2 l2 = new Level2();
                l2.Visible = true;
                this.Close();
            }
        }


        private void Form1_MouseMove(object sender, MouseEventArgs e)
        {
            controller.moveController(e.X);
            this.Invalidate();
        }


        private void GameThreadProc()
        {
            //Image img = Breakout.Properties.Resources.airadventurelevel4;
            //this.BackgroundImage = img;
            //this.BackgroundImageLayout = ImageLayout.Stretch;
            
            IAsyncResult tick = null;
            while (!_evExit.WaitOne(15))
            {
                if (tick != null)
                {
                    if (!tick.AsyncWaitHandle.WaitOne(0))
                    {
                        // we are running too slow, maybe we can do something about it
                        if (WaitHandle.WaitAny(
                            new WaitHandle[]
                            {
                                _evExit,
                                tick.AsyncWaitHandle
                            }) == 0)
                        {
                            return;
                        }
                    }
                }
                if (!endGame)
                {
                    tick = BeginInvoke(new MethodInvoker(OnGameTimerTick));
                }
                
            }
        }


        private void OnGameTimerTick()
        {
            ball.moveBall();
            bp.moveSpecial();
            if (bp.check_specialDrop(ball, controller))
            {
                    // triple ball if I have time
            }
            ball.controlCollision(controller.ControllerForm);
            ball.brickCollision(bp.brick_Pattern);
            int numberCol = bp.check_Collisions(ball.ball_Form);
           
            if (numberCol > 0)
            {
                points += numberCol;
                Point_count.Text = points.ToString();
            }
            if(points == 75)
            {
                endGame = true;
                win_Menu.Visible = true;
            }
            if (!ball.collider())
            {
                take_life();
                if (lifes == 0)
                {

                    endGame = true;
                    gameOver.Visible = true;

                }
                else
                {
                    GameRestart();
                }


            }

            this.Invalidate();
        }

        private void ExitGame()
        {
            Close();
        }




        private void take_life()
        {
            if (Heart_Container1.Visible)
            {
                Heart_Container1.Visible = false;
            }
            else if (Heart_Container2.Visible)
            {
                Heart_Container2.Visible = false;
            }
            else if (Heart_Container3.Visible)
            {
                Heart_Container3.Visible = false;
            }
            else if (Heart_Container4.Visible)
            {
                Heart_Container4.Visible = false;
            }
            lifes -= 1;
        }

        
        private void GameRestart()
        {
            
            ball = new Ball();
            controller = new Controller();
            gameOver.Visible = false;
            

        }

        private void gameOverScreen()
        {
            gameOver.Visible =true;
        }

        private void GameReset()
        {
            ball = new Ball();
            controller = new Controller();
            initiate_Lifes();
            bp.reset_pattern();
            endGame = false;
            win_Menu.Visible = false;
            points = 0;
            Point_count.Text = points.ToString();
        }
        protected override void OnClosed(EventArgs e)
        {
            base.OnClosed(e);
        }

    }
}

namespace Breakout
{
    partial class Level2
    {
        /// <summary>
        /// Required designer variable.
        /// </summary>
        private System.ComponentModel.IContainer components = null;

        /// <summary>
        /// Clean up any resources being used.
        /// </summary>
        /// <param name="disposing">true if managed resources should be disposed; otherwise, false.</param>
        protected override void Dispose(bool disposing)
        {
            if (disposing && (components != null))
            {
                components.Dispose();
            }
            base.Dispose(disposing);
        }

        #region Código gerado pelo Windows Form Designer

        /// <summary>
        /// Required method for Designer support - do not modify
        /// the contents of this method with the code editor.
        /// </summary>
        private void InitializeComponent()
        {
            System.ComponentModel.ComponentResourceManager resources = new System.ComponentModel.ComponentResourceManager(typeof(Level2));
            this.Brick_Label = new System.Windows.Forms.LinkLabel();
            this.Point_count = new System.Windows.Forms.Label();
            this.gameOver = new System.Windows.Forms.Label();
            this.HeartContainer1 = new System.Windows.Forms.PictureBox();
            this.HeartContainer2 = new System.Windows.Forms.PictureBox();
            this.HeartContainer3 = new System.Windows.Forms.PictureBox();
            this.HeartContainer4 = new System.Windows.Forms.PictureBox();
            this.winMenu = new System.Windows.Forms.Label();
            this.MusicPlayer = new AxWMPLib.AxWindowsMediaPlayer();
            this.fps = new System.Windows.Forms.TextBox();
            ((System.ComponentModel.ISupportInitialize)(this.HeartContainer1)).BeginInit();
            ((System.ComponentModel.ISupportInitialize)(this.HeartContainer2)).BeginInit();
            ((System.ComponentModel.ISupportInitialize)(this.HeartContainer3)).BeginInit();
            ((System.ComponentModel.ISupportInitialize)(this.HeartContainer4)).BeginInit();
            ((System.ComponentModel.ISupportInitialize)(this.MusicPlayer)).BeginInit();
            this.SuspendLayout();
            // 
            // Brick_Label
            // 
            this.Brick_Label.ActiveLinkColor = System.Drawing.Color.White;
            this.Brick_Label.Anchor = ((System.Windows.Forms.AnchorStyles)((System.Windows.Forms.AnchorStyles.Bottom | System.Windows.Forms.AnchorStyles.Left)));
            this.Brick_Label.AutoSize = true;
            this.Brick_Label.BackColor = System.Drawing.Color.Transparent;
            this.Brick_Label.Font = new System.Drawing.Font("Open Sans", 36F, System.Drawing.FontStyle.Bold, System.Drawing.GraphicsUnit.Point, ((byte)(0)));
            this.Brick_Label.ImageAlign = System.Drawing.ContentAlignment.BottomLeft;
            this.Brick_Label.LinkColor = System.Drawing.Color.Red;
            this.Brick_Label.Location = new System.Drawing.Point(2, 555);
            this.Brick_Label.Name = "Brick_Label";
            this.Brick_Label.Size = new System.Drawing.Size(517, 98);
            this.Brick_Label.TabIndex = 0;
            this.Brick_Label.TabStop = true;
            this.Brick_Label.Text = "Bricks Broke:";
            this.Brick_Label.TextAlign = System.Drawing.ContentAlignment.BottomLeft;
            this.Brick_Label.PreviewKeyDown += new System.Windows.Forms.PreviewKeyDownEventHandler(this.Form1_PreviewKeyDown);
            // 
            // Point_count
            // 
            this.Point_count.Anchor = ((System.Windows.Forms.AnchorStyles)((System.Windows.Forms.AnchorStyles.Bottom | System.Windows.Forms.AnchorStyles.Left)));
            this.Point_count.AutoSize = true;
            this.Point_count.BackColor = System.Drawing.Color.Transparent;
            this.Point_count.Font = new System.Drawing.Font("Open Sans", 36F, System.Drawing.FontStyle.Bold, System.Drawing.GraphicsUnit.Point, ((byte)(0)));
            this.Point_count.Location = new System.Drawing.Point(541, 555);
            this.Point_count.Name = "Point_count";
            this.Point_count.Size = new System.Drawing.Size(83, 98);
            this.Point_count.TabIndex = 1;
            this.Point_count.Text = "0";
            this.Point_count.TextAlign = System.Drawing.ContentAlignment.BottomLeft;
            this.Point_count.PreviewKeyDown += new System.Windows.Forms.PreviewKeyDownEventHandler(this.Form1_PreviewKeyDown);
            // 
            // gameOver
            // 
            this.gameOver.Anchor = System.Windows.Forms.AnchorStyles.None;
            this.gameOver.AutoSize = true;
            this.gameOver.BackColor = System.Drawing.Color.White;
            this.gameOver.Font = new System.Drawing.Font("Open Sans", 36F, System.Drawing.FontStyle.Bold, System.Drawing.GraphicsUnit.Point, ((byte)(0)));
            this.gameOver.Location = new System.Drawing.Point(12, 36);
            this.gameOver.Name = "gameOver";
            this.gameOver.Size = new System.Drawing.Size(714, 490);
            this.gameOver.TabIndex = 2;
            this.gameOver.Text = "Game Over\r\n\r\nPress F1 to Restart\r\nPress Esc to Exit\r\n\r\n";
            this.gameOver.TextAlign = System.Drawing.ContentAlignment.MiddleCenter;
            this.gameOver.PreviewKeyDown += new System.Windows.Forms.PreviewKeyDownEventHandler(this.Form1_PreviewKeyDown);
            // 
            // HeartContainer1
            // 
            this.HeartContainer1.Anchor = ((System.Windows.Forms.AnchorStyles)((System.Windows.Forms.AnchorStyles.Top | System.Windows.Forms.AnchorStyles.Right)));
            this.HeartContainer1.BackColor = System.Drawing.Color.Transparent;
            this.HeartContainer1.ErrorImage = global::Breakout.Properties.Resources.heart_container;
            this.HeartContainer1.Image = global::Breakout.Properties.Resources.heart_container;
            this.HeartContainer1.InitialImage = global::Breakout.Properties.Resources.heart_container;
            this.HeartContainer1.Location = new System.Drawing.Point(636, 36);
            this.HeartContainer1.Name = "HeartContainer1";
            this.HeartContainer1.Size = new System.Drawing.Size(19, 15);
            this.HeartContainer1.SizeMode = System.Windows.Forms.PictureBoxSizeMode.StretchImage;
            this.HeartContainer1.TabIndex = 3;
            this.HeartContainer1.TabStop = false;
            // 
            // HeartContainer2
            // 
            this.HeartContainer2.Anchor = ((System.Windows.Forms.AnchorStyles)((System.Windows.Forms.AnchorStyles.Top | System.Windows.Forms.AnchorStyles.Right)));
            this.HeartContainer2.BackColor = System.Drawing.Color.Transparent;
            this.HeartContainer2.ErrorImage = global::Breakout.Properties.Resources.heart_container;
            this.HeartContainer2.Image = global::Breakout.Properties.Resources.heart_container;
            this.HeartContainer2.InitialImage = global::Breakout.Properties.Resources.heart_container;
            this.HeartContainer2.Location = new System.Drawing.Point(677, 36);
            this.HeartContainer2.Name = "HeartContainer2";
            this.HeartContainer2.Size = new System.Drawing.Size(22, 15);
            this.HeartContainer2.SizeMode = System.Windows.Forms.PictureBoxSizeMode.StretchImage;
            this.HeartContainer2.TabIndex = 4;
            this.HeartContainer2.TabStop = false;
            // 
            // HeartContainer3
            // 
            this.HeartContainer3.Anchor = ((System.Windows.Forms.AnchorStyles)((System.Windows.Forms.AnchorStyles.Top | System.Windows.Forms.AnchorStyles.Right)));
            this.HeartContainer3.BackColor = System.Drawing.Color.Transparent;
            this.HeartContainer3.ErrorImage = global::Breakout.Properties.Resources.heart_container;
            this.HeartContainer3.Image = global::Breakout.Properties.Resources.heart_container;
            this.HeartContainer3.InitialImage = global::Breakout.Properties.Resources.heart_container;
            this.HeartContainer3.Location = new System.Drawing.Point(716, 36);
            this.HeartContainer3.Name = "HeartContainer3";
            this.HeartContainer3.Size = new System.Drawing.Size(19, 15);
            this.HeartContainer3.SizeMode = System.Windows.Forms.PictureBoxSizeMode.StretchImage;
            this.HeartContainer3.TabIndex = 5;
            this.HeartContainer3.TabStop = false;
            // 
            // HeartContainer4
            // 
            this.HeartContainer4.Anchor = ((System.Windows.Forms.AnchorStyles)((System.Windows.Forms.AnchorStyles.Top | System.Windows.Forms.AnchorStyles.Right)));
            this.HeartContainer4.BackColor = System.Drawing.Color.Transparent;
            this.HeartContainer4.ErrorImage = global::Breakout.Properties.Resources.heart_container;
            this.HeartContainer4.Image = global::Breakout.Properties.Resources.heart_container;
            this.HeartContainer4.InitialImage = global::Breakout.Properties.Resources.heart_container;
            this.HeartContainer4.Location = new System.Drawing.Point(751, 36);
            this.HeartContainer4.Name = "HeartContainer4";
            this.HeartContainer4.Size = new System.Drawing.Size(19, 15);
            this.HeartContainer4.SizeMode = System.Windows.Forms.PictureBoxSizeMode.StretchImage;
            this.HeartContainer4.TabIndex = 6;
            this.HeartContainer4.TabStop = false;
            // 
            // winMenu
            // 
            this.winMenu.Anchor = System.Windows.Forms.AnchorStyles.None;
            this.winMenu.AutoSize = true;
            this.winMenu.BackColor = System.Drawing.Color.White;
            this.winMenu.Font = new System.Drawing.Font("Microsoft Sans Serif", 36F, System.Drawing.FontStyle.Bold, System.Drawing.GraphicsUnit.Point, ((byte)(0)));
            this.winMenu.Location = new System.Drawing.Point(28, 94);
            this.winMenu.Name = "winMenu";
            this.winMenu.Size = new System.Drawing.Size(671, 328);
            this.winMenu.TabIndex = 7;
            this.winMenu.Text = "You Win!\r\n\r\nPress F1 to Restart\r\nPress Esc to Exit";
            this.winMenu.TextAlign = System.Drawing.ContentAlignment.MiddleCenter;
            this.winMenu.Visible = false;
            // 
            // MusicPlayer
            // 
            this.MusicPlayer.Enabled = true;
            this.MusicPlayer.Location = new System.Drawing.Point(13, 36);
            this.MusicPlayer.Name = "MusicPlayer";
            this.MusicPlayer.OcxState = ((System.Windows.Forms.AxHost.State)(resources.GetObject("MusicPlayer.OcxState")));
            this.MusicPlayer.Size = new System.Drawing.Size(75, 23);
            this.MusicPlayer.TabIndex = 8;
            this.MusicPlayer.Visible = false;
            // 
            // fps
            // 
            this.fps.Location = new System.Drawing.Point(29, 65);
            this.fps.Name = "fps";
            this.fps.Size = new System.Drawing.Size(100, 26);
            this.fps.TabIndex = 9;
            // 
            // Level2
            // 
            this.AutoScaleDimensions = new System.Drawing.SizeF(9F, 20F);
            this.AutoScaleMode = System.Windows.Forms.AutoScaleMode.Font;
            this.BackColor = System.Drawing.Color.White;
            this.ClientSize = new System.Drawing.Size(840, 653);
            this.Controls.Add(this.fps);
            this.Controls.Add(this.MusicPlayer);
            this.Controls.Add(this.winMenu);
            this.Controls.Add(this.HeartContainer4);
            this.Controls.Add(this.HeartContainer3);
            this.Controls.Add(this.HeartContainer2);
            this.Controls.Add(this.HeartContainer1);
            this.Controls.Add(this.gameOver);
            this.Controls.Add(this.Point_count);
            this.Controls.Add(this.Brick_Label);
            this.DoubleBuffered = true;
            this.ForeColor = System.Drawing.SystemColors.ActiveCaptionText;
            this.FormBorderStyle = System.Windows.Forms.FormBorderStyle.None;
            this.KeyPreview = true;
            this.Name = "Level2";
            this.Text = "Form1";
            this.Paint += new System.Windows.Forms.PaintEventHandler(this.Form1_Paint);
            this.MouseMove += new System.Windows.Forms.MouseEventHandler(this.Form1_MouseMove);
            this.PreviewKeyDown += new System.Windows.Forms.PreviewKeyDownEventHandler(this.Form1_PreviewKeyDown);
            ((System.ComponentModel.ISupportInitialize)(this.HeartContainer1)).EndInit();
            ((System.ComponentModel.ISupportInitialize)(this.HeartContainer2)).EndInit();
            ((System.ComponentModel.ISupportInitialize)(this.HeartContainer3)).EndInit();
            ((System.ComponentModel.ISupportInitialize)(this.HeartContainer4)).EndInit();
            ((System.ComponentModel.ISupportInitialize)(this.MusicPlayer)).EndInit();
            this.ResumeLayout(false);
            this.PerformLayout();

        }

        #endregion
        private System.Windows.Forms.LinkLabel Brick_Label;
        private System.Windows.Forms.Label Point_count;
        private System.Windows.Forms.Label gameOver;
        private System.Windows.Forms.PictureBox HeartContainer1;
        private System.Windows.Forms.PictureBox HeartContainer2;
        private System.Windows.Forms.PictureBox HeartContainer3;
        private System.Windows.Forms.PictureBox HeartContainer4;
        private System.Windows.Forms.Label winMenu;
        private AxWMPLib.AxWindowsMediaPlayer MusicPlayer;
        private System.Windows.Forms.TextBox fps;
    }
}


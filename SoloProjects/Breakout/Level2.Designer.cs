namespace Breakout
{
    partial class Form1
    {
        /// <summary>
        /// Variável de designer necessária.
        /// </summary>
        private System.ComponentModel.IContainer components = null;

        /// <summary>
        /// Limpar os recursos que estão sendo usados.
        /// </summary>
        /// <param name="disposing">true se for necessário descartar os recursos gerenciados; caso contrário, false.</param>
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
        /// Método necessário para suporte ao Designer - não modifique 
        /// o conteúdo deste método com o editor de código.
        /// </summary>
        private void InitializeComponent()
        {
            System.ComponentModel.ComponentResourceManager resources = new System.ComponentModel.ComponentResourceManager(typeof(Form1));
            this.Brick_Label = new System.Windows.Forms.LinkLabel();
            this.Point_count = new System.Windows.Forms.Label();
            this.gameOver = new System.Windows.Forms.Label();
            this.Heart_Container1 = new System.Windows.Forms.PictureBox();
            this.Heart_Container2 = new System.Windows.Forms.PictureBox();
            this.Heart_Container3 = new System.Windows.Forms.PictureBox();
            this.Heart_Container4 = new System.Windows.Forms.PictureBox();
            this.win_Menu = new System.Windows.Forms.Label();
            this.MusicPlayer = new AxWMPLib.AxWindowsMediaPlayer();
            ((System.ComponentModel.ISupportInitialize)(this.Heart_Container1)).BeginInit();
            ((System.ComponentModel.ISupportInitialize)(this.Heart_Container2)).BeginInit();
            ((System.ComponentModel.ISupportInitialize)(this.Heart_Container3)).BeginInit();
            ((System.ComponentModel.ISupportInitialize)(this.Heart_Container4)).BeginInit();
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
            // Heart_Container1
            // 
            this.Heart_Container1.Anchor = ((System.Windows.Forms.AnchorStyles)((System.Windows.Forms.AnchorStyles.Top | System.Windows.Forms.AnchorStyles.Right)));
            this.Heart_Container1.BackColor = System.Drawing.Color.Transparent;
            this.Heart_Container1.ErrorImage = global::Breakout.Properties.Resources.heart_container;
            this.Heart_Container1.Image = global::Breakout.Properties.Resources.heart_container;
            this.Heart_Container1.InitialImage = global::Breakout.Properties.Resources.heart_container;
            this.Heart_Container1.Location = new System.Drawing.Point(636, 36);
            this.Heart_Container1.Name = "Heart_Container1";
            this.Heart_Container1.Size = new System.Drawing.Size(19, 15);
            this.Heart_Container1.SizeMode = System.Windows.Forms.PictureBoxSizeMode.StretchImage;
            this.Heart_Container1.TabIndex = 3;
            this.Heart_Container1.TabStop = false;
            // 
            // Heart_Container2
            // 
            this.Heart_Container2.Anchor = ((System.Windows.Forms.AnchorStyles)((System.Windows.Forms.AnchorStyles.Top | System.Windows.Forms.AnchorStyles.Right)));
            this.Heart_Container2.BackColor = System.Drawing.Color.Transparent;
            this.Heart_Container2.ErrorImage = global::Breakout.Properties.Resources.heart_container;
            this.Heart_Container2.Image = global::Breakout.Properties.Resources.heart_container;
            this.Heart_Container2.InitialImage = global::Breakout.Properties.Resources.heart_container;
            this.Heart_Container2.Location = new System.Drawing.Point(677, 36);
            this.Heart_Container2.Name = "Heart_Container2";
            this.Heart_Container2.Size = new System.Drawing.Size(22, 15);
            this.Heart_Container2.SizeMode = System.Windows.Forms.PictureBoxSizeMode.StretchImage;
            this.Heart_Container2.TabIndex = 4;
            this.Heart_Container2.TabStop = false;
            // 
            // Heart_Container3
            // 
            this.Heart_Container3.Anchor = ((System.Windows.Forms.AnchorStyles)((System.Windows.Forms.AnchorStyles.Top | System.Windows.Forms.AnchorStyles.Right)));
            this.Heart_Container3.BackColor = System.Drawing.Color.Transparent;
            this.Heart_Container3.ErrorImage = global::Breakout.Properties.Resources.heart_container;
            this.Heart_Container3.Image = global::Breakout.Properties.Resources.heart_container;
            this.Heart_Container3.InitialImage = global::Breakout.Properties.Resources.heart_container;
            this.Heart_Container3.Location = new System.Drawing.Point(716, 36);
            this.Heart_Container3.Name = "Heart_Container3";
            this.Heart_Container3.Size = new System.Drawing.Size(19, 15);
            this.Heart_Container3.SizeMode = System.Windows.Forms.PictureBoxSizeMode.StretchImage;
            this.Heart_Container3.TabIndex = 5;
            this.Heart_Container3.TabStop = false;
            // 
            // Heart_Container4
            // 
            this.Heart_Container4.Anchor = ((System.Windows.Forms.AnchorStyles)((System.Windows.Forms.AnchorStyles.Top | System.Windows.Forms.AnchorStyles.Right)));
            this.Heart_Container4.BackColor = System.Drawing.Color.Transparent;
            this.Heart_Container4.ErrorImage = global::Breakout.Properties.Resources.heart_container;
            this.Heart_Container4.Image = global::Breakout.Properties.Resources.heart_container;
            this.Heart_Container4.InitialImage = global::Breakout.Properties.Resources.heart_container;
            this.Heart_Container4.Location = new System.Drawing.Point(751, 36);
            this.Heart_Container4.Name = "Heart_Container4";
            this.Heart_Container4.Size = new System.Drawing.Size(19, 15);
            this.Heart_Container4.SizeMode = System.Windows.Forms.PictureBoxSizeMode.StretchImage;
            this.Heart_Container4.TabIndex = 6;
            this.Heart_Container4.TabStop = false;
            // 
            // win_Menu
            // 
            this.win_Menu.Anchor = System.Windows.Forms.AnchorStyles.None;
            this.win_Menu.AutoSize = true;
            this.win_Menu.BackColor = System.Drawing.Color.White;
            this.win_Menu.Font = new System.Drawing.Font("Microsoft Sans Serif", 36F, System.Drawing.FontStyle.Bold, System.Drawing.GraphicsUnit.Point, ((byte)(0)));
            this.win_Menu.Location = new System.Drawing.Point(28, 94);
            this.win_Menu.Name = "win_Menu";
            this.win_Menu.Size = new System.Drawing.Size(671, 328);
            this.win_Menu.TabIndex = 7;
            this.win_Menu.Text = "You Win!\r\n\r\nPress F1 to Restart\r\nPress Esc to Exit";
            this.win_Menu.TextAlign = System.Drawing.ContentAlignment.MiddleCenter;
            this.win_Menu.Visible = false;
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
            // Form1
            // 
            this.AutoScaleDimensions = new System.Drawing.SizeF(9F, 20F);
            this.AutoScaleMode = System.Windows.Forms.AutoScaleMode.Font;
            this.BackColor = System.Drawing.Color.White;
            this.ClientSize = new System.Drawing.Size(840, 653);
            this.Controls.Add(this.MusicPlayer);
            this.Controls.Add(this.win_Menu);
            this.Controls.Add(this.Heart_Container4);
            this.Controls.Add(this.Heart_Container3);
            this.Controls.Add(this.Heart_Container2);
            this.Controls.Add(this.Heart_Container1);
            this.Controls.Add(this.gameOver);
            this.Controls.Add(this.Point_count);
            this.Controls.Add(this.Brick_Label);
            this.DoubleBuffered = true;
            this.ForeColor = System.Drawing.SystemColors.ActiveCaptionText;
            this.FormBorderStyle = System.Windows.Forms.FormBorderStyle.None;
            this.KeyPreview = true;
            this.Name = "Form1";
            this.Text = "Form1";
            this.Paint += new System.Windows.Forms.PaintEventHandler(this.Form1_Paint);
            this.MouseMove += new System.Windows.Forms.MouseEventHandler(this.Form1_MouseMove);
            this.PreviewKeyDown += new System.Windows.Forms.PreviewKeyDownEventHandler(this.Form1_PreviewKeyDown);
            ((System.ComponentModel.ISupportInitialize)(this.Heart_Container1)).EndInit();
            ((System.ComponentModel.ISupportInitialize)(this.Heart_Container2)).EndInit();
            ((System.ComponentModel.ISupportInitialize)(this.Heart_Container3)).EndInit();
            ((System.ComponentModel.ISupportInitialize)(this.Heart_Container4)).EndInit();
            ((System.ComponentModel.ISupportInitialize)(this.MusicPlayer)).EndInit();
            this.ResumeLayout(false);
            this.PerformLayout();

        }

        #endregion
        private System.Windows.Forms.LinkLabel Brick_Label;
        private System.Windows.Forms.Label Point_count;
        private System.Windows.Forms.Label gameOver;
        private System.Windows.Forms.PictureBox Heart_Container1;
        private System.Windows.Forms.PictureBox Heart_Container2;
        private System.Windows.Forms.PictureBox Heart_Container3;
        private System.Windows.Forms.PictureBox Heart_Container4;
        private System.Windows.Forms.Label win_Menu;
        private AxWMPLib.AxWindowsMediaPlayer MusicPlayer;
    }
}


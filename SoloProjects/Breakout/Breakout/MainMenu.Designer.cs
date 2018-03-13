namespace Breakout
{
    partial class MainMenu
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

        #region Windows Form Designer generated code

        /// <summary>
        /// Required method for Designer support - do not modify
        /// the contents of this method with the code editor.
        /// </summary>
        private void InitializeComponent()
        {
            this.startGame = new System.Windows.Forms.Button();
            this.GameTitle = new System.Windows.Forms.Label();
            this.ExitGameButton = new System.Windows.Forms.Button();
            this.SuspendLayout();
            // 
            // startGame
            // 
            this.startGame.Location = new System.Drawing.Point(300, 211);
            this.startGame.Name = "startGame";
            this.startGame.Size = new System.Drawing.Size(99, 32);
            this.startGame.TabIndex = 0;
            this.startGame.Text = "Start Game";
            this.startGame.UseVisualStyleBackColor = true;
            this.startGame.Click += new System.EventHandler(this.startGame_Click);
            // 
            // GameTitle
            // 
            this.GameTitle.AutoSize = true;
            this.GameTitle.Font = new System.Drawing.Font("Open Sans Semibold", 36F, ((System.Drawing.FontStyle)((System.Drawing.FontStyle.Bold | System.Drawing.FontStyle.Italic))), System.Drawing.GraphicsUnit.Point, ((byte)(0)));
            this.GameTitle.ForeColor = System.Drawing.Color.DarkRed;
            this.GameTitle.Location = new System.Drawing.Point(63, 47);
            this.GameTitle.Name = "GameTitle";
            this.GameTitle.Size = new System.Drawing.Size(549, 98);
            this.GameTitle.TabIndex = 1;
            this.GameTitle.Text = "Super Breakout";
            this.GameTitle.TextAlign = System.Drawing.ContentAlignment.TopCenter;
            // 
            // ExitGameButton
            // 
            this.ExitGameButton.Location = new System.Drawing.Point(300, 284);
            this.ExitGameButton.Name = "ExitGameButton";
            this.ExitGameButton.Size = new System.Drawing.Size(99, 35);
            this.ExitGameButton.TabIndex = 2;
            this.ExitGameButton.Text = "Exit Game";
            this.ExitGameButton.UseVisualStyleBackColor = true;
            this.ExitGameButton.Click += new System.EventHandler(this.ExitGameButton_Click);
            // 
            // MainMenu
            // 
            this.AutoScaleDimensions = new System.Drawing.SizeF(9F, 20F);
            this.AutoScaleMode = System.Windows.Forms.AutoScaleMode.Font;
            this.BackgroundImageLayout = System.Windows.Forms.ImageLayout.Center;
            this.ClientSize = new System.Drawing.Size(681, 415);
            this.Controls.Add(this.ExitGameButton);
            this.Controls.Add(this.GameTitle);
            this.Controls.Add(this.startGame);
            this.Name = "MainMenu";
            this.Text = "MainMenu";
            this.ResumeLayout(false);
            this.PerformLayout();

        }

        #endregion

        private System.Windows.Forms.Button startGame;
        private System.Windows.Forms.Label GameTitle;
        private System.Windows.Forms.Button ExitGameButton;
    }
}
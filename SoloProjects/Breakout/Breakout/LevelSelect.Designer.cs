namespace Breakout
{
    partial class LevelSelect
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
            this.label1 = new System.Windows.Forms.Label();
            this.Level1 = new System.Windows.Forms.Button();
            this.Level2 = new System.Windows.Forms.Button();
            this.ExitGame = new System.Windows.Forms.Button();
            this.SuspendLayout();
            // 
            // label1
            // 
            this.label1.AutoSize = true;
            this.label1.Font = new System.Drawing.Font("Open Sans Semibold", 36F, System.Drawing.FontStyle.Bold, System.Drawing.GraphicsUnit.Point, ((byte)(0)));
            this.label1.ForeColor = System.Drawing.Color.DarkRed;
            this.label1.Location = new System.Drawing.Point(41, 38);
            this.label1.Name = "label1";
            this.label1.Size = new System.Drawing.Size(447, 98);
            this.label1.TabIndex = 0;
            this.label1.Text = "Level Select";
            this.label1.TextAlign = System.Drawing.ContentAlignment.TopCenter;
            // 
            // Level1
            // 
            this.Level1.Location = new System.Drawing.Point(230, 191);
            this.Level1.Name = "Level1";
            this.Level1.Size = new System.Drawing.Size(91, 37);
            this.Level1.TabIndex = 1;
            this.Level1.Text = "Level 1";
            this.Level1.UseVisualStyleBackColor = true;
            this.Level1.Click += new System.EventHandler(this.Level1_Click);
            // 
            // Level2
            // 
            this.Level2.Location = new System.Drawing.Point(230, 258);
            this.Level2.Name = "Level2";
            this.Level2.Size = new System.Drawing.Size(91, 33);
            this.Level2.TabIndex = 2;
            this.Level2.Text = "Level 2";
            this.Level2.UseVisualStyleBackColor = true;
            this.Level2.Click += new System.EventHandler(this.Level2_Click);
            // 
            // ExitGame
            // 
            this.ExitGame.Location = new System.Drawing.Point(230, 330);
            this.ExitGame.Name = "ExitGame";
            this.ExitGame.Size = new System.Drawing.Size(91, 32);
            this.ExitGame.TabIndex = 4;
            this.ExitGame.Text = "Exit";
            this.ExitGame.UseVisualStyleBackColor = true;
            this.ExitGame.Click += new System.EventHandler(this.ExitGame_Click);
            // 
            // LevelSelect
            // 
            this.AutoScaleDimensions = new System.Drawing.SizeF(9F, 20F);
            this.AutoScaleMode = System.Windows.Forms.AutoScaleMode.Font;
            this.ClientSize = new System.Drawing.Size(534, 387);
            this.Controls.Add(this.ExitGame);
            this.Controls.Add(this.Level2);
            this.Controls.Add(this.Level1);
            this.Controls.Add(this.label1);
            this.Name = "LevelSelect";
            this.Text = "LevelSelect";
            this.ResumeLayout(false);
            this.PerformLayout();

        }

        #endregion

        private System.Windows.Forms.Label label1;
        private System.Windows.Forms.Button Level1;
        private System.Windows.Forms.Button Level2;
        private System.Windows.Forms.Button ExitGame;
    }
}
//*******************************************************************************************//
//                                                                                           //
// Download Free Evaluation Version From: https://bytescout.com/download/web-installer       //
//                                                                                           //
// Also available as Web API! Free Trial Sign Up: https://secure.bytescout.com/users/sign_up //
//                                                                                           //
// Copyright © 2017-2018 ByteScout Inc. All rights reserved.                                 //
// http://www.bytescout.com                                                                  //
//                                                                                           //
//*******************************************************************************************//


using System;
using Bytescout.PDF2HTML;

namespace ExtractHTML
{
	class Program
	{
		static void Main(string[] args)
		{
			// Create Bytescout.PDF2HTML.HTMLExtractor instance
			HTMLExtractor extractor = new HTMLExtractor();
			extractor.RegistrationName = "demo";
			extractor.RegistrationKey = "demo";

			// Set plain HTML extraction mode
			extractor.ExtractionMode = HTMLExtractionMode.PlainHTML;

			// Load sample PDF document
			extractor.LoadDocumentFromFile("sample2.pdf");

			// Save extracted HTML to file
			extractor.SaveHtmlToFile("output.html");

			// Open output file in default associated application
			System.Diagnostics.Process.Start("output.html");
		}
	}
}
